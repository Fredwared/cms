<?php
namespace App\Http\Controllers\Backend\Utils;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class SearchController extends BackendController
{
    public function group(Request $request)
    {
        $q = $request->q ?? null;
        $item = check_paging($request->item);
        $page = $request->page ?? 1;

        //get model
        $businessGroup = Globals::getBusiness('Group');

        $arrData = $businessGroup->searchGroup([
            'keyword' => $q,
            'item' => $item,
            'page' => $page
        ]);

        if ($arrData->count() > 0) {
            $arrData = $arrData->toArray();
            return response()->json(['total' => $arrData['total'], 'items' => $arrData['data']]);
        } else {
            return response()->json(['total' => 0, 'items' => []]);
        }
    }

    public function user(Request $request)
    {
        $q = $request->q ?? null;
        $item = check_paging($request->item);
        $page = $request->page ?? 1;

        //get model
        $businessUser = Globals::getBusiness('User');

        $arrData = $businessUser->searchUser([
            'keyword' => $q,
            'item' => $item,
            'page' => $page
        ]);

        if ($arrData->count() > 0) {
            $arrData = $arrData->toArray();
            return response()->json(['total' => $arrData['total'], 'items' => $arrData['data']]);
        } else {
            return response()->json(['total' => 0, 'items' => []]);
        }
    }

    public function medialabel(Request $request)
    {
        $q = $request->q ?? null;
        $type = $request->t ?? 1;
        $item = check_paging($request->item);
        $page = $request->page ?? 1;

        //get model
        $businessMediaLabel = Globals::getBusiness('Media_Label');

        $arrData = $businessMediaLabel->searchMediaLabel([
            'media_type' => $type,
            'keyword' => $q,
            'item' => $item,
            'page' => $page
        ]);

        if ($arrData->count() > 0) {
            $arrData = $arrData->toArray();
            return response()->json(['total' => $arrData['total'], 'items' => $arrData['data']]);
        } else {
            return response()->json(['total' => 0, 'items' => []]);
        }
    }

    public function tag(Request $request)
    {
        $q = $request->q ?? null;
        $language = check_language($request->l);
        $item = check_paging($request->item);
        $page = $request->page ?? 1;

        //get model
        $businessTag = Globals::getBusiness('Tag');

        $arrData = $businessTag->searchTag([
            'keyword' => $q,
            'language_id' => $language,
            'item' => $item,
            'page' => $page
        ]);

        if ($arrData->count() > 0) {
            $arrData = $arrData->toArray();
            return response()->json(['total' => $arrData['total'], 'items' => $arrData['data']]);
        } else {
            return response()->json(['total' => 0, 'items' => []]);
        }
    }
}
