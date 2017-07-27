<?php
namespace App\Console\Commands;

use App\Jobs\CrawlerVNE;
use App\Models\Services\Globals;
use Illuminate\Console\Command;

class CrawlerCategoryVNE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:categoryvne';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler to get category from VNE.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $request = Globals::makeRequest('http://vnexpress.net');

        if ($request) {
            $crawler = $request['crawler'];
            $businessCategory = Globals::getBusiness('Category');
            $businessLangMap = Globals::getBusiness('LangMap');

            $crawler->filter('ul#menu_web li')->each(function ($node, $i) use ($businessCategory, $businessLangMap) {
                if (!empty($node->text())) {
                    $link = $node->filter('a')->first()->attr('href');
                    if (!str_contains($link, 'vnexpress.net')) {
                        $link = 'http://vnexpress.net' . $link;
                    }
                    
                    //insert into table category, update if exist
                    $category_title = trim($node->text());
                    if (!empty($category_title)) {
                        $category_code = str_slug($category_title);
                        if (!in_array($category_code, ['video', 'rao-vat'])) {
                            $categoryinfo = $businessCategory->updateOrCreate([
                                'category_code' => str_slug($category_title)
                            ], [
                                'category_title' => $category_title,
                                'category_code' => str_slug($category_title),
                                'category_order' => $i,
                                'cateparent_id' => 0,
                                'language_id' => config('app.locale'),
                                'status' => config('cms.backend.status.active'),
                            ]);

                            //insert into table category, update if exist
                            $businessLangMap->updateOrCreate([
                                'item_module' => 'category',
                                'item_id' => $categoryinfo->category_id,
                                'language_id' => $categoryinfo->language_id
                            ], [
                                'item_module' => 'category',
                                'item_id' => $categoryinfo->category_id,
                                'language_id' => $categoryinfo->language_id,
                                'source_item_id' => $categoryinfo->category_id,
                                'source_language_id' => null,
                            ]);

                            //get sub category
                            dispatch(new CrawlerVNE([
                                'type' => 'category',
                                'link' => $link,
                                'parent_id' => $categoryinfo->category_id,
                                'level' => 1
                            ]));
                        }
                    }
                }
            });
        }
    }
}
