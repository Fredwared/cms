<?php
namespace App\Http\Controllers\Backend\Utils;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class EditorController extends BackendController
{
    public function insertPost(Request $request)
    {
        //get model
        $businessCategory = Globals::getBusiness('Category');
        $businessArticle = Globals::getBusiness('Article');

        $language_id = check_language($request->language_id);
        $category_id = $request->category_id ?? null;
        $title = $request->title ?? null;
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $page = $request->page ?? 1;
        $item = check_paging($request->item);
        $editor_name = $request->editor_name ?? 'article_content';

        // get list category active;
        $arrListCategory = $businessCategory->getListCategory([
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active')
        ]);

        // get list post;
        $params = [
            'category_id' => $category_id,
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active'),
            'title' => $title,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'item' => $item,
            'page' => $page,
            'editor_name' => $editor_name
        ];
        $arrListArticle = $businessArticle->getListArticle($params);
        $pagination = $arrListArticle->appends($params)->links();

        return view('backend.utils.editor.insertpost', compact('arrListCategory', 'arrListArticle', 'category_id', 'language_id', 'title', 'date_from', 'date_to', 'item', 'pagination', 'editor_name'));
    }

    public function insertVideo(Request $request)
    {
        $item = check_paging($request->item);
        $page = $request->page ?? 1;
        $media_source = $request->media_source ?? null;
        $label = $request->label ?? [];
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $type = config('cms.backend.media.type.video');
        $editor_name = $request->editor_name ?? 'article_content';

        //get model
        $businessMedia = Globals::getBusiness('Media');

        // get list block ip active
        $params = [
            'media_type' => $type,
            'media_source' => $media_source,
            'label' => $label,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'item' => $item,
            'page' => $page,
            'editor_name' => $editor_name
        ];
        $arrListVideo = $businessMedia->getListMedia($params);

        if ($arrListVideo->total() > 0) {
            $maxPage = ceil($arrListVideo->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.media.video.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        unset($params['media_type']);
        $pagination = $arrListVideo->appends($params)->links();

        return view('backend.utils.editor.insertvideo', compact('arrListVideo', 'pagination', 'item', 'type', 'media_source', 'label', 'date_from', 'date_to', 'editor_name'));
    }

    public function insertLink(Request $request)
    {
        return view('backend.utils.editor.insertlink');
    }
}
