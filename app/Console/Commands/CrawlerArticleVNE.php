<?php
namespace App\Console\Commands;

use App\Jobs\CrawlerVNE;
use App\Models\Services\Globals;
use Illuminate\Console\Command;

class CrawlerArticleVNE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:articlevne';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler to get article from VNE.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //get list category
        $businessCategory = Globals::getBusiness('Category');
        $arrCategory = $businessCategory->getListCategory([
            'cateparent_id' =>  0,
            'language_id' => config('app.locale'),
            'status' => config('cms.backend.status.active')
        ]);

        foreach ($arrCategory as $category) {
            $request = Globals::makeRequest('http://vnexpress.net/rss/' . $category->category_code . '.rss');

            if ($request) {
                $crawler = $request['crawler'];

                $crawler->filter('item')->each(function ($node) {
                    if ($node) {
                        $link = $node->filter('link')->first()->text();

                        if (str_contains($link, 'vnexpress.net/tin-tuc/') || str_contains($link, 'vnexpress.net/photo/') || str_contains($link, 'vnexpress.net/infographics/')) {
                            dispatch(new CrawlerVNE(['type' => 'article', 'link' => $link]));
                        }
                    }
                });
            }
        }
    }
}
