<?php
namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Services\Globals;
use App\Models\Services\Crawler;

class CrawlerVNE extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    protected $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->params['type'] == 'category') {
            $this->getSubcateVNE($this->params['link'], $this->params['parent_id'], $this->params['level']);
        } else {
            $this->getArticleVNE($this->params['link']);
        }
    }
    
    private function getSubcateVNE($link, $parentId, $intLevel)
    {
        $request = Globals::makeRequest($link);

        if ($request) {
            $crawler = $request['crawler'];
            $selector = $intLevel == 1 ? 'ul#breakumb_web a' : 'ul.sub_breakumn a';
            $businessCategory = Globals::getBusiness('Category');
            $businessLangMap = Globals::getBusiness('LangMap');
            
            $crawler->filter($selector)->each(function ($node, $i) use ($parentId, $intLevel, $businessCategory, $businessLangMap) {
                $link = $node->attr('href');
                if (!str_contains($link, 'vnexpress.net')) {
                    $link = 'http://vnexpress.net' . $link;
                }
    
                //insert into table category, update if exist
                $category_title = trim($node->text());
                if (!empty($category_title)) {
                    $categoryInfo = $businessCategory->updateOrCreate([
                        'category_code' => str_slug($category_title),
                        'language_id' => config('app.locale')
                    ], [
                        'category_title' => $category_title,
                        'category_code' => str_slug($category_title),
                        'category_order' => $i,
                        'cateparent_id' => $parentId,
                        'language_id' => config('app.locale'),
                        'status' => config('cms.backend.status.active'),
                    ]);

                    //insert into table category, update if exist
                    $businessLangMap->updateOrCreate([
                        'item_module' => 'category',
                        'item_id' => $categoryInfo->category_id,
                        'language_id' => $categoryInfo->language_id
                    ], [
                        'item_module' => 'category',
                        'item_id' => $categoryInfo->category_id,
                        'language_id' => $categoryInfo->language_id,
                        'source_item_id' => $categoryInfo->category_id,
                        'source_language_id' => null,
                    ]);

                    //get sub category
                    if ($intLevel == 1) {
                        $this->getSubcateVNE($link, $categoryInfo->category_id, $intLevel + 1);
                    }
                }
            });
        }
    }

    private function getArticleVNE($link)
    {
        $arrType = [
            'tin-tuc' => config('cms.backend.article.type.post'),
            'photo' => config('cms.backend.article.type.album'),
            'infographics' => config('cms.backend.article.type.infographic')
        ];

        $regex = '/(.*)\/(tin-tuc|photo|infographics)\/([a-z0-9_-]+)\/([a-z0-9_-]+)?\/?([a-zA_Z0-9_-]+)-([\d]+)(-p([\d]+))?\.html(.*)/';
        preg_match($regex, $link, $matches);

        if (!empty($matches)) {
            $businessCategory = Globals::getBusiness('Category');

            $categoryInfo = $businessCategory->findByAttributes([
                'category_code' => $matches[4],
                'language_id' => config('app.locale')
            ]);
            if (!$categoryInfo) {
                $categoryInfo = $businessCategory->findByAttributes([
                    'category_code' => $matches[3],
                    'language_id' => config('app.locale')
                ]);
            }

            if ($categoryInfo) {
                $businessLangMap = Globals::getBusiness('LangMap');
                $businessArticle = Globals::getBusiness('Article');
                $businessArticleCategory = Globals::getBusiness('Article_Category');

                $article_type = $arrType[$matches[2]];
                $arrData = Crawler::getContentNews($link);

                $arrCategoryListon = $businessCategory->getFullParentId($categoryInfo->category_id);
                $arrCategoryListon[] = $categoryInfo->category_id;

                $articleInfo = $businessArticle->updateOrCreate([
                    'article_code' => str_slug(clean($arrData['title'], 'notags')),
                    'language_id' => config('app.locale')
                ], [
                    'article_title' => str_limit(clean($arrData['title'], 'notags'), 250, ''),
                    'article_code' => str_slug(clean($arrData['title'], 'notags')),
                    'article_description' => clean($arrData['description'], 'notags'),
                    'article_content' => $arrData['content'],
                    'language_id' => config('app.locale'),
                    'category_id' => $categoryInfo->category_id,
                    'category_liston' => implode(',', $arrCategoryListon),
                    'user_id' => config('cms.backend.super_admin_id'),
                    'status' => config('cms.backend.article.status.pending'),
                ]);

                //create share_url
                $articleInfo->update([
                    'share_url' => config('app.url') . '/' . $articleInfo->article_code . '-' . $articleInfo->article_id . '.html'
                ]);
                
                //insert into table article_category
                $businessArticleCategory->addByArticle($articleInfo->article_id, $arrCategoryListon);

                //insert into table langmap
                $businessLangMap->updateOrCreate([
                    'item_module' => 'article',
                    'item_id' => $articleInfo->article_id,
                    'language_id' => $articleInfo->language_id
                ], [
                    'item_module' => 'article',
                    'item_id' => $articleInfo->article_id,
                    'language_id' => $articleInfo->language_id,
                    'source_item_id' => $articleInfo->article_id,
                    'source_language_id' => null,
                ]);

                //parse content if this is album or infographic
                if ($article_type == config('cms.backend.article.type.album')) {
                    $this->getArticleAlbum($articleInfo);
                } elseif ($article_type == config('cms.backend.article.type.infographic')) {
                    $this->getArticleInfographic($articleInfo);
                }
            }
        }
    }

    private function getArticleAlbum($articleInfo)
    {
        $businessMedia = Globals::getBusiness('Media');
        $businessArticleMedia = Globals::getBusiness('Article_Media');

        $content = preg_replace(array('/\r?\n/', '/\t/'), '', $articleInfo->article_content);
        preg_match_all('/<img(.[^<]*)>/', $content, $images);

        if (is_array($images)) {
            $arrData = $businessMedia->insertMediaFromCrawler($images[0]);
            $businessArticleMedia->addByArticle($articleInfo->article_id, $arrData['media']);
            unset($arrData['media']);

            $article_content = '';
            if (!empty($arrData)) {
                foreach ($arrData as $id => $data) {
                    $article_content .= '<div class="img-wrap"><div class="img-thumb"><img src="' . image_url($data['filename']) . '" class="img-responsive" title="' . $articleInfo->article_title . '" /></div><div class="img-caption">' . $data['caption'] . '</div></div>';
                }
            }
            $articleInfo->update([
                'article_content' => $article_content
            ]);
        }
    }

    private function getArticleInfographic($articleInfo)
    {
        $businessMedia = Globals::getBusiness('Media');
        $businessArticleMedia = Globals::getBusiness('Article_Media');

        $content = preg_replace(array('/\r?\n/', '/\t/'), '', $articleInfo->article_content);
        preg_match_all('/<img(.[^<]*)>/', $content, $images);

        if (is_array($images)) {
            $arrData = $businessMedia->insertMediaFromCrawler($images[0]);
            $businessArticleMedia->addByArticle($articleInfo->article_id, $arrData['media']);
            unset($arrData['media']);

            $article_content = '';
            if (!empty($arrData)) {
                foreach ($arrData as $id => $data) {
                    $article_content .= '<div class="img-wrap"><div class="img-thumb"><img src="' . image_url($data['filename']) . '" class="img-responsive" title="' . $articleInfo->article_title . '" /></div></div>';
                }
            }
            $articleInfo->update([
                'article_content' => $article_content
            ]);
        }
    }
}
