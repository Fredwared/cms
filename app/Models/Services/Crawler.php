<?php
namespace App\Models\Services;

class Crawler
{
    public static function getContentNews($link)
    {
        $arrData = [];
        $request = Globals::makeRequest($link);

        if ($request) {
            $crawler = $request['crawler'];

            if (str_contains($link, 'vnexpress.net')) {
                $arrData = self::getContentVNE($crawler);
            } elseif (str_contains($link, 'tuoitre.vn')) {
                $arrData = self::getContentTuoitre($crawler);
            } elseif (str_contains($link, 'dantri.com.vn')) {
                $arrData = self::getContentDantri($crawler);
            } elseif (str_contains($link, 'soha.vn')) {
                $arrData = self::getContentSoha($crawler);
            } elseif (str_contains($link, 'ngoisao.net')) {
                $arrData = self::getContentNgoisao($crawler);
            }
        }

        return $arrData;
    }

    private static function getContentVNE($crawler)
    {
        $title = $description = $content = '';
        
        if (count($crawler->filter('div.title_news h1')) > 0) {
            $title = $crawler->filter('div.title_news h1')->first()->text();
        }
        
        if (count($crawler->filter('h3.short_intro')) > 0) {
            $description = $crawler->filter('h3.short_intro')->first()->text();
        } elseif (count($crawler->filter('div.short_intro')) > 0) {
            $description = $crawler->filter('div.short_intro')->first()->text();
        }
        
        if (count($crawler->filter('div#article_content')) > 0) {
            $content = $crawler->filter('div#article_content')->first()->html();
        } elseif (count($crawler->filter('div.fck_detail')) > 0) {
            $content = $crawler->filter('div.fck_detail')->first()->html();
            $content = preg_replace('/\n\r?/', '', $content);
            $content = preg_replace('/ class="Normal"/', '', $content);
            $content = preg_replace('/class="tplCaption"/', 'class="img-wrap"', $content);
            $content = preg_replace('/class="Image"/', 'class="img-caption"', $content);
            $content = preg_replace('/class="tbl_insert"/', 'class="quote"', $content);
        }

        return ['title' => trim($title), 'description' => trim($description), 'content' => $content];
    }

    private static function getContentTuoitre($crawler)
    {
        $title = $description = $content = '';
        
        if (count($crawler->filter('a#object_title')) > 0) {
            $title = $crawler->filter('a#object_title')->first()->text();
        }
        
        if (count($crawler->filter('p.txt-head')) > 0) {
            $description = $crawler->filter('p.txt-head')->first()->text();
        }
        
        if (count($crawler->filter('div.fck')) > 0) {
            $content = $crawler->filter('div.fck')->first()->html();
            $content = preg_replace('/\n\r?/', '', $content);
            $content = preg_replace('/ class="ck_inner_title"/', 'class="inner-title"', $content);
            $content = preg_replace('/ class="ck_question"/', 'class="inner-question"', $content);
            $content = preg_replace('/class="desc_image slide_content"/', 'class="img-wrap"', $content);
            $content = preg_replace('/class="ck_legend caption"/', 'class="img-caption"', $content);
            $content = preg_replace('/class="dbox dbox-center"/', 'class="quote"', $content);
        }
        
        return ['title' => trim($title), 'description' => trim($description), 'content' => $content];
    }

    private static function getContentDantri($crawler)
    {
        $title = $description = $content = '';
        
        if (count($crawler->filter('h1.mgb15')) > 0) {
            $title = $crawler->filter('h1.mgb15')->first()->text();
        }
        
        if (count($crawler->filter('h2.sapo')) > 0) {
            $description = $crawler->filter('h2.sapo')->first()->text();
        }
        
        if (count($crawler->filter('div#divNewsContent')) > 0) {
            $content = $crawler->filter('div#divNewsContent')->first()->html();
            $content = preg_replace('/\n\r?/', '', $content);
            $content = preg_replace('/class="VCSortableInPreviewMode"/', 'class="img-wrap"', $content);
            $content = preg_replace('/class="PhotoCMS_Caption"/', 'class="img-caption"', $content);
        }

        return ['title' => trim($title), 'description' => trim($description), 'content' => $content];
    }

    private static function getContentSoha($crawler)
    {
        $title = $description = $content = '';
        
        if (count($crawler->filter('h1.news-title')) > 0) {
            $title = $crawler->filter('h1.news-title')->first()->text();
        }
        
        if (count($crawler->filter('h2.news-sapo')) > 0) {
            $description = $crawler->filter('h2.news-sapo')->first()->text();
        }
        
        if (count($crawler->filter('div.news-content')) > 0) {
            $content = $crawler->filter('div.news-content')->first()->html();
            $content = preg_replace('/\n\r?/', '', $content);
            $content = preg_replace('/class="VCSortableInPreviewMode"/', 'class="img-wrap"', $content);
            $content = preg_replace('/class="PhotoCMS_Caption"/', 'class="img-caption"', $content);
        }

        return ['title' => trim($title), 'description' => trim($description), 'content' => $content];
    }

    private static function getContentNgoisao($crawler)
    {
        $title = $description = $content = '';
        
        if (count($crawler->filter('h1.title')) > 0) {
            $title = $crawler->filter('h1.title')->first()->text();
        }
        
        if (count($crawler->filter('p.lead')) > 0) {
            $description = $crawler->filter('p.lead')->first()->text();
        }
        
        if (count($crawler->filter('div.fck_detail')) > 0) {
            $content = $crawler->filter('div.fck_detail')->first()->html();
            $content = preg_replace('/\n\r?/', '', $content);
            $content = preg_replace('/ class="Normal"/', '', $content);
            $content = preg_replace('/class="tplCaption"/', 'class="img-wrap"', $content);
            $content = preg_replace('/class="Image"/', 'class="img-caption"', $content);
            $content = preg_replace('/class="tbl_insert"/', 'class="quote"', $content);
        }
        
        return ['title' => trim($title), 'description' => trim($description), 'content' => $content];
    }
}
