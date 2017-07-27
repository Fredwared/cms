<?php
namespace App\Http\Controllers\Backend\ConfigWebsite;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Navigation;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class NavigationController extends BackendController
{
    public function index(Request $request)
    {
        $language_id = check_language($request->language_id);

        //get model
        $businessNavigation = Globals::getBusiness('Navigation');
        $businessCategory = Globals::getBusiness('Category');
        $businessPage = Globals::getBusiness('Page');

        // get list navigation;
        $arrListNavigation = $businessNavigation->getListNavigation([
            'language_id' => $language_id
        ]);

        // get list navigation level 1;
        $arrListParent = $businessNavigation->getListNavigation([
            'language_id' => $language_id
        ]);

        $arrLanguage = config('laravellocalization.supportedLocales');

        // get list page;
        $arrListPage = $businessPage->getListPage([
            'language_id' => $language_id,
            'status' => config('cms.backend.status.article')
        ]);

        // get list category article;
        $arrListCategoryArticle = $businessCategory->getListCategory([
            'cateparent_id' => 0,
            'language_id' => $language_id,
            'status' => config('cms.backend.status.article'),
            'category_type' =>  config('cms.backend.category.type.article')
        ]);

        // get list category product;
        $arrListCategoryProduct = $businessCategory->getListCategory([
            'cateparent_id' => 0,
            'language_id' => $language_id,
            'status' => config('cms.backend.status.article'),
            'category_type' =>  config('cms.backend.category.type.product')
        ]);

        if (!$request->ajax()) {
            return view('backend.configwebsite.navigation.index', compact('arrListNavigation', 'arrListParent', 'arrLanguage', 'arrListCategoryArticle', 'arrListCategoryProduct', 'arrListPage', 'language_id'));
        } else {
            return view('backend.configwebsite.navigation.list', compact('arrListNavigation', 'arrListParent'));
        }
    }

    public function store(Request $request, $language, $type)
    {
        if ($type == 'custom') {
            $rules = [
                'navigation_title' => 'required|max:200',
                'navigation_url' => 'url|unique:navigation,navigation_url,null,null,deleted,0,language_id,' . $language
            ];
        } else {
            $rules = [
                'navigation_type_id' => 'required'
            ];
        }

        $this->validate($request, $rules, [
            'navigation_title.required' => trans('validation.navigation.navigation_title.required'),
            'navigation_title.max' => trans('validation.navigation.navigation_title.maxlength'),
            'navigation_url.url' => trans('validation.navigation.navigation_url.invalid'),
            'navigation_url.unique' => trans('validation.navigation.navigation_url.unique'),
            'navigation_type_id.required' => trans('validation.navigation.navigation_type_id.required')
        ]);

        //get model
        $businessNavigation = Globals::getBusiness('Navigation');

        $parent_id = $request->parent_id ?? 0;
        $params = [
            'navigation_order' => $businessNavigation->getLastOrder($parent_id, $language),
            'navigation_type' => $type,
            'parent_id' => $parent_id,
            'language_id' => $language
        ];

        if ($type == 'custom') {
            $params['navigation_title'] = clean($request->navigation_title, 'notags');
            $params['navigation_url'] = $request->navigation_url ?? null;

            $businessNavigation->create($params);
        } else {
            foreach ($request->navigation_type_id as $type_id => $title) {
                $params['navigation_title'] = clean($title, 'notags');
                $params['navigation_type_id'] = $type_id;

                $businessNavigation->create($params);
            }
        }

        return response()->json(['error' => 0, 'message' => trans('common.messages.navigation.created'), 'parents' => $this->getListParent($language)]);
    }

    public function update(Request $request, Business_Navigation $navigationInfo)
    {
        $this->validate($request, [
            'navigation_title' => 'required|max:200',
            'navigation_url' => 'url|unique:navigation,navigation_url,' . $navigationInfo->navigation_id . ',navigation_id,deleted,0,language_id,' . $navigationInfo->language_id
        ], [
            'navigation_title.required' => trans('validation.navigation.navigation_title.required'),
            'navigation_title.max' => trans('validation.navigation.navigation_title.maxlength'),
            'navigation_url.url' => trans('validation.navigation.navigation_url.invalid'),
            'navigation_url.unique' => trans('validation.navigation.navigation_url.unique'),
            'navigation_type_id.required' => trans('validation.navigation.navigation_type_id.required')
        ]);

        if (in_array($request->parent_id, $navigationInfo->childs->pluck('navigation_id')->toArray())) {
            return response()->json(['error' => 1, 'message' => trans('validation.navigation.parent_id.invalid')]);
        }

        $navigationInfo->update([
            'navigation_title' => clean($request->navigation_title, 'notags'),
            'navigation_url' => $request->navigation_url ?? $navigationInfo->navigation_url,
            'parent_id' => $request->parent_id ?? $navigationInfo->parent_id
        ]);

        return response()->json(['error' => 0, 'message' => trans('common.messages.navigation.updated'), 'parents' => $this->getListParent($navigationInfo->language_id)]);
    }

    public function destroy(Business_Navigation $navigationInfo)
    {
        $language_id = $navigationInfo->language_id;

        $navigationInfo->delete();

        return response()->json(['error' => 0, 'message' => trans('common.messages.navigation.deleted'), 'parents' => $this->getListParent($language_id)]);
    }

    public function sort(Request $request, $language)
    {
        //get model
        $businessNavigation = Globals::getBusiness('Navigation');

        $arrId = $request->id ?? [];

        if (!empty($arrId)) {
            foreach ($arrId as $index => $id) {
                $businessNavigation->find($id)->update([
                    'navigation_order' => $index + 1
                ]);
            }
        }

        return response()->json(['error' => 0, 'message' => trans('common.messages.sort_success'), 'parents' => $this->getListParent($language)]);
    }

    private function getListParent($language_id)
    {
        //get model
        $businessNavigation = Globals::getBusiness('Navigation');

        // get list navigation level 1;
        $arrListParent = $businessNavigation->getListNavigation([
            'language_id' => $language_id
        ]);

        $sHTML = '<option value="0">--</option>';
        foreach ($arrListParent as $navigation) {
            $sHTML .= '<option value="' . $navigation->navigation_id . '">' . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $navigation->navigation_level - 1) . $navigation->navigation_title . '</option>';
        }

        return $sHTML;
    }
}
