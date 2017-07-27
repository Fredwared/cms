<?php
namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class BuildtopController extends BackendController
{
    public function index(Request $request)
    {
        // get model
        $businessBuildtop = Globals::getBusiness('Buildtop');
        $businessCategory = Globals::getBusiness('Category');

        // get param
        $language_id = check_language($request->language_id);
        $category_id = $request->category_id ?? 0;

        // get list category;
        $arrListCategory = $businessCategory->getListCategory([
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active'),
            'category_type' =>  config('cms.backend.category.type.product')
        ]);

        // get list buildtop
        $arrListBuildtop = $businessBuildtop->getListBuildtop([
            'type' => config('cms.backend.buildtop.type.product'),
            'category_id' => $category_id,
            'language_id' => $language_id
        ]);

        //get languages
        $arrLanguage = config('laravellocalization.supportedLocales');

        if ($request->ajax()) {
            return view('backend.product.buildtop.listbuildtop', compact('arrListBuildtop'))->render();
        }

        return view('backend.product.buildtop.index', compact('arrListCategory', 'arrListBuildtop', 'arrLanguage', 'language_id', 'category_id'));
    }

    public function listProduct(Request $request)
    {
        // get model
        $businessProduct = Globals::getBusiness('Product');

        // get param
        $language_id = check_language($request->language_id);
        $category_id = $request->category_id ?? 0;
        $title = $request->title ?? null;
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $page = $request->page ?? 1;
        $item = check_paging($request->item);

        // get list article;
        $params = [
            'category_id' => $category_id,
            'language_id' => $language_id,
            'status' => config('cms.backend.status.active'),
            'title' => $title,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'item' => $item,
            'page' => $page
        ];
        $arrListProduct = $businessProduct->getListProduct($params);
        $pagination = $arrListProduct->appends($params)->links();

        return view('backend.product.buildtop.listproduct', compact('arrListProduct', 'pagination', 'item'))->render();
    }

    public function save(Request $request)
    {
        // get model
        $businessBuildtop = Globals::getBusiness('Buildtop');

        $arrId = $request->id ?? [];

        foreach ($arrId as $index => $id) {
            if (starts_with($id, 'new_') !== false) {
                $id = str_replace('new_', '', $id);

                $businessBuildtop->create([
                    'type' => config('cms.backend.buildtop.type.product'),
                    'category_id' => $request->category_id,
                    'type_id' => $id,
                    'order' => $index + 1,
                    'language_id' => $request->language_id,
                ]);
            } else {
                $businessBuildtop->find($id)->update([
                    'order' => $index + 1
                ]);
            }
        }

        return response()->json(['error' => 0, 'message' => trans('common.messages.buildtop.updated')]);
    }

    public function destroy(Request $request)
    {
        // get model
        $businessBuildtop = Globals::getBusiness('Buildtop');

        $arrId = $request->id ?? [];

        foreach ($arrId as $index => $id) {
            if (starts_with($id, 'new_') === false) {
                $businessBuildtop->find($id)->delete();
            }
        }

        return response()->json(['error' => 0, 'message' => trans('common.messages.buildtop.deleted')]);
    }
}
