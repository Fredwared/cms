<?php
namespace App\Http\Controllers\Backend\Utils;

use App\Http\Controllers\BackendController;
use App\Models\Services\Crawler;
use Illuminate\Http\Request;

class CrawlerController extends BackendController
{
    public function parseLink(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('backend.utils.crawler.parselink');
        } else {
            $this->validate($request, [
                'link' => 'required|url'
            ], [
                'link.required' => trans('validation.crawler.link.required'),
                'link.url' => trans('validation.crawler.link.invalid')
            ]);

            $link = $request->link;
            $arrData = Crawler::getContentNews($link);

            if (!empty($arrData)) {
                return response()->json(['error' => 0, 'message' => 'Parse link to get content complete!', 'data' => $arrData]);
            }

            return response()->json(['error' => 1, 'message' => 'No data.']);
        }
    }
}
