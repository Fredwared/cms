<?php
namespace App\Http\Controllers\Backend\Article;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use App\Models\Entity\Business\Business_Category;

class CategoryController extends BackendController
{
    public function index(Request $request)
    {
        $status = $request->status ?? null;
        $language_id = check_language($request->language_id);
        $category_type = config('cms.backend.category.type.article');
        $page = $request->page ?? 1;
        $item = check_paging($request->item);

        //get model
        $businessCategory = Globals::getBusiness('Category');
        
        // get list category active;
        $params = [
            'language_id' => $language_id,
            'status' => $status,
            'category_type' =>  $category_type,
            'item' => $item,
            'page' => $page
        ];
        $arrListCategory = $businessCategory->getListCategory($params);
        
        if ($arrListCategory->total() > 0) {
            $maxPage = ceil($arrListCategory->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.article.category.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        unset($params['category_type']);
        $pagination = $arrListCategory->appends($params)->links();
        
        $arrLanguage = config('laravellocalization.supportedLocales');
        
        return view('backend.article.category.index', compact('arrListCategory', 'arrLanguage', 'status', 'language_id', 'category_type', 'pagination', 'item'));
    }

    public function create(Request $request, $language = null)
    {
        $language = check_language($language);
        $category_source = $request->source_item_id ?? 0;
        $item_id = $request->item_id ?? null;
        $category_type = config('cms.backend.category.type.article');

        //get model
        $businessCategory = Globals::getBusiness('Category');

        //get languages
        $arrLanguage = config('laravellocalization.supportedLocales');

        //check language is valid or not
        if (!in_array($language, array_keys($arrLanguage))) {
            return redirect(route('backend.article.category.create', [config('app.locale')]));
        }

        // get detail category source
        $categorySourceInfo = null;
        if ($category_source && $language != config('app.locale')) {
            $categorySourceInfo = $businessCategory->find($category_source);
        }

        return view('backend.article.category.create', compact('arrLanguage', 'language', 'category_source', 'item_id', 'category_type', 'categorySourceInfo'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category_title' => 'required|max:200',
            'category_code' => 'required|regex:[^[a-z0-9\-]+$]|max:200|unique:category,category_code,null,null,category_type,' . config('cms.backend.category.type.article') . ',deleted,0,language_id,' . $request->language_id,
            'cateparent_id' => 'required',
            'language_id' => 'required|in:' . implode(',', array_keys(config('laravellocalization.supportedLocales'))),
            'category_source' => 'required_unless:language_id,' . config('app.locale') . '|exists:category,category_id,status,' . config('cms.backend.status.active'),
            'category_order' => 'integer',
            'category_showfe' => 'in:1,0',
            'category_icon' => 'max:50',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'category_title.required' => trans('validation.category.category_title.required'),
            'category_title.max' => trans('validation.category.category_title.maxlength'),
            'category_code.required' => trans('validation.category.category_code.required'),
            'category_code.regex' => trans('validation.category.category_code.code'),
            'category_code.max' => trans('validation.category.category_code.maxlength'),
            'category_code.unique' => trans('validation.category.category_code.unique'),
            'cateparent_id.required' => trans('validation.category.cateparent_id.required'),
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid'),
            'category_source.required_unless' => trans('validation.category.category_source.required'),
            'category_source.exists' => trans('validation.category.category_source.not_exist'),
            'category_order.integer' => trans('validation.category.category_order.number'),
            'category_showfe' => trans('validation.category.category_showfe.invalid'),
            'category_icon.max' => trans('validation.category.category_icon.maxlength'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessCategory = Globals::getBusiness('Category');
        $businessLangMap = Globals::getBusiness('LangMap');

        $category_order = $request->category_order;
        if (empty($category_order)) {
            $category_order = $businessCategory->getLastOrder($request->cateparent_id, $request->language_id);
        }
        
        $params = [
            'category_title' => clean($request->category_title, 'notags'),
            'category_code' => clean($request->category_code, 'notags'),
            'category_description' => clean($request->category_description, 'notags'),
            'category_order' => $category_order,
            'category_seo_title' => clean($request->category_seo_title, 'notags'),
            'category_seo_keywords' => clean($request->category_seo_keywords, 'notags'),
            'category_seo_description' => clean($request->category_seo_description, 'notags'),
            'category_showfe' => $request->category_showfe,
            'category_icon' => $request->category_icon ?? null,
            'cateparent_id' => $request->cateparent_id,
            'language_id' => $request->language_id,
            'status' => $request->status,
            'category_type' => $request->category_type,
        ];
        $categoryInfo = $businessCategory->create($params);
        
        //insert into table langmap
        $category_source = $request->category_source ?? null;
        $params = [
            'item_module' => 'category',
            'item_id' => $categoryInfo->category_id,
            'language_id' => $categoryInfo->language_id,
            'source_item_id' => $categoryInfo->category_id,
            'source_language_id' => null,
        ];
        if (!empty($category_source)) {
            $categorySourceInfo = $businessCategory->find($category_source);
            
            $params['source_item_id'] = $categorySourceInfo->category_id;
            $params['source_language_id'] = $categorySourceInfo->language_id;

            $categoryInfo->update([
                'category_code' => $categorySourceInfo->category_code
            ]);
        }
        
        $businessLangMap->updateOrCreate([
            'item_module' => 'category',
            'item_id' => $categoryInfo->category_id,
            'language_id' => $categoryInfo->language_id
        ], $params);
        
        $item_id = $request->item_id ?? null;
        if (!empty($item_id)) {
            $itemInfo = $businessCategory->find($item_id);
            
            if ($itemInfo) {
                $businessLangMap->updateOrCreate([
                    'item_module' => 'category',
                    'item_id' => $itemInfo->category_id,
                    'language_id' => $itemInfo->language_id
                ], [
                    'item_module' => 'category',
                    'item_id' => $itemInfo->category_id,
                    'language_id' => $itemInfo->language_id,
                    'source_item_id' => $categoryInfo->category_id,
                    'source_language_id' => $categoryInfo->language_id,
                ]);
            }
        }

        flash()->success(trans('common.messages.category.created'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.article.category.edit', [$categoryInfo->category_id]));
        }

        return redirect(route('backend.article.category.index', ['language_id' => $request->language_id]));
    }

    public function edit(Business_Category $categoryInfo)
    {
        if ($categoryInfo->category_type == config('cms.backend.category.type.product')) {
            return redirect(route('backend.product.category.edit', [$categoryInfo->category_id]));
        }

        //get model
        $businessLangMap = Globals::getBusiness('LangMap');
        
        $arrLanguage = config('laravellocalization.supportedLocales');
        
        $categorySource = $businessLangMap->findByAttributes([
            'item_module' => 'category',
            'item_id' => $categoryInfo->category_id,
            'language_id' => $categoryInfo->language_id
        ]);
        
        return view('backend.article.category.edit', compact('arrLanguage', 'categoryInfo', 'categorySource'));
    }

    public function update(Request $request, Business_Category $categoryInfo)
    {
        $this->validate($request, [
            'category_title' => 'required|max:200',
            'category_code' => 'required|regex:[^[a-z0-9\-]+$]|max:200|unique:category,category_code,' . $categoryInfo->category_id . ',category_id,category_type,' . config('cms.backend.category.type.article') . ',deleted,0,language_id,' . $request->language_id,
            'cateparent_id' => 'required',
            'language_id' => 'in:' . implode(',', array_keys(config('laravellocalization.supportedLocales'))),
            'category_source' => 'required_unless:language_id,' . config('app.locale') . '|exists:category,category_id,status,' . config('cms.backend.status.active'),
            'category_order' => 'integer',
            'category_showfe' => 'in:1,0',
            'category_icon' => 'max:50',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'category_title.required' => trans('validation.category.category_title.required'),
            'category_title.max' => trans('validation.category.category_title.maxlength'),
            'category_code.required' => trans('validation.category.category_code.required'),
            'category_code.regex' => trans('validation.category.category_code.code'),
            'category_code.max' => trans('validation.category.category_code.maxlength'),
            'category_code.unique' => trans('validation.category.category_code.unique'),
            'cateparent_id.required' => trans('validation.category.cateparent_id.required'),
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid'),
            'category_source.required_unless' => trans('validation.category.category_source.required'),
            'category_source.exists' => trans('validation.category.category_source.not_exist'),
            'category_order.integer' => trans('validation.category.category_order.number'),
            'category_showfe' => trans('validation.category.category_showfe.invalid'),
            'category_icon.max' => trans('validation.category.category_icon.maxlength'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessCategory = Globals::getBusiness('Category');
        $businessLangMap = Globals::getBusiness('LangMap');
        
        $category_order = $request->category_order;
        if (empty($category_order)) {
            $category_order = $businessCategory->getLastOrder($request->cateparent_id, $request->language_id);
        }

        //save old language
        $old_language_id = $categoryInfo->language_id;
        
        $params = [
            'category_title' => clean($request->category_title, 'notags'),
            'category_code' => clean($request->category_code, 'notags'),
            'category_description' => clean($request->category_description, 'notags'),
            'category_order' => $category_order,
            'category_seo_title' => clean($request->category_seo_title, 'notags'),
            'category_seo_keywords' => clean($request->category_seo_keywords, 'notags'),
            'category_seo_description' => clean($request->category_seo_description, 'notags'),
            'category_showfe' => $request->category_showfe,
            'category_icon' => $request->category_icon ?? null,
            'cateparent_id' => $request->cateparent_id,
            'language_id' => $categoryInfo->articles->count() <= 0 ? $request->language_id : $categoryInfo->language_id,
            'status' => $request->status,
        ];
        $categoryInfo->update($params);
        
        //insert into table langmap
        $category_source = $request->category_source ?? null;
        $params = [
            'item_module' => 'category',
            'item_id' => $categoryInfo->category_id,
            'language_id' => $categoryInfo->language_id,
            'source_item_id' => $categoryInfo->category_id,
            'source_language_id' => null,
        ];
        if (!empty($category_source)) {
            $categorySourceInfo = $businessCategory->find($category_source);
        
            $params['source_item_id'] = $categorySourceInfo->category_id;
            $params['source_language_id'] = $categorySourceInfo->language_id;

            $categoryInfo->update([
                'category_code' => $categorySourceInfo->category_code
            ]);
        }
        
        $businessLangMap->updateOrCreate([
            'item_module' => 'category',
            'item_id' => $categoryInfo->category_id,
            'language_id' => $old_language_id
        ], $params);
        
        flash()->success(trans('common.messages.category.updated'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.article.category.edit', [$categoryInfo->category_id]));
        }
        
        return redirect(route('backend.article.category.index', ['language_id' => $request->language_id]));
    }

    public function destroy(Request $request, Business_Category $categoryInfo)
    {
        $categoryInfo->delete();

        //get model
        $businessLangMap = Globals::getBusiness('LangMap');
        
        //delete from table langmap
        $businessLangMap->forceDeleteByAttributes([
            'item_module' => 'category',
            'item_id' => $categoryInfo->category_id,
            'language_id' => $categoryInfo->language_id
        ]);
        
        $businessLangMap->forceDeleteByAttributes([
            'item_module' => 'category',
            'source_item_id' => $categoryInfo->category_id
        ]);

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.category.deleted'));
    
            return redirect(route('backend.article.category.index', ['language_id' => $categoryInfo->language_id]));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.category.deleted')]);
        }
    }

    public function changeStatus(Request $request, $status)
    {
        if (!$request->ajax()) {
            return redirect('404');
        }
        
        if (!in_array($status, array_values(config('cms.backend.status')))) {
            return response()->json(['error' => 1, 'message' => trans('validation.status.invalid')]);
        }
        
        $arrId = $request->has('id') ? $request->id : [];

        if (!empty($arrId)) {
            $arrId = (array)$arrId;

            //get model
            $businessCategory = Globals::getBusiness('Category');

            foreach ($arrId as $id) {
                $businessCategory->find($id)->update([
                    'status' => $status
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success')]);
        } else {
            return response()->json(['error' => 1, 'message' => trans('common.messages.changestatus_error')]);
        }
    }

    public function sort(Request $request, $language)
    {
        //get model
        $businessCategory = Globals::getBusiness('Category');

        if ($request->isMethod('get')) {
            $category_type = config('cms.backend.category.type.article');

            // get list category active;
            $arrListCategory = $businessCategory->getListCategory([
                'cateparent_id' =>  0,
                'language_id' => $language,
                'category_type' => $category_type
            ]);

            return view('backend.article.category.sort', compact('arrListCategory', 'language', 'category_type'));
        } else {
            foreach ($request->category[0] as $parent_order => $parent_id) {
                $businessCategory->find($parent_id)->update([
                    'category_order' => $parent_order + 1
                ]);

                if (isset($request->category[$parent_id])) {
                    foreach ($request->category[$parent_id] as $child_order => $child_id) {
                        $businessCategory->find($child_id)->update([
                            'category_order' => $child_order + 1
                        ]);

                        if (isset($request->category[$child_id])) {
                            foreach ($request->category[$child_id] as $order => $id) {
                                $businessCategory->find($id)->update([
                                    'category_order' => $order + 1
                                ]);
                            }
                        }
                    }
                }
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.sort_success')]);
        }
    }
}
