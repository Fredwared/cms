<?php
namespace App\Http\Controllers\Backend\ConfigWebsite;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Page;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class PageController extends BackendController
{
    public function index(Request $request)
    {
        $status = $request->status ?? null;
        $language_id = check_language($request->language_id);
        $page = $request->page ?? 1;
        $item = check_paging($request->item);

        //get model
        $businessPage = Globals::getBusiness('Page');

        // get list config;
        $params = [
            'language_id' => $language_id,
            'status' => $status,
            'item' => $item,
            'page' => $page
        ];
        $arrListPage = $businessPage->getListPage($params);

        if ($arrListPage->total() > 0) {
            $maxPage = ceil($arrListPage->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.configwebsite.page.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListPage->appends($params)->links();

        $arrLanguage = config('laravellocalization.supportedLocales');

        return view('backend.configwebsite.page.index', compact('arrListPage', 'arrLanguage', 'pagination', 'item', 'language_id', 'status'));
    }

    public function create(Request $request, $language = null)
    {
        $language = check_language($language);
        $page_source = $request->source_item_id ?? 0;
        $item_id = $request->item_id ?? null;

        //get model
        $businessPage = Globals::getBusiness('Page');

        //get languages
        $arrLanguage = config('laravellocalization.supportedLocales');

        //check language is valid or not
        if (!in_array($language, array_keys($arrLanguage))) {
            return redirect(route('backend.configwebsite.page.create', [config('app.locale')]));
        }

        // get detail page source
        $pageSourceInfo = null;
        if ($page_source && $language != config('app.locale')) {
            $pageSourceInfo = $businessPage->find($page_source);
        }

        return view('backend.configwebsite.page.create', compact('arrLanguage', 'language', 'page_source', 'item_id', 'pageSourceInfo'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'page_title' => 'required|max:200',
            'page_code' => 'required|regex:[^[a-z0-9\-]+$]|max:200|unique:page,page_code,null,null,deleted,0,language_id,' . $request->language_id,
            'parent_id' => 'required',
            'language_id' => 'required|in:' . implode(',', array_keys(config('laravellocalization.supportedLocales'))),
            'page_source' => 'required_unless:language_id,' . config('app.locale') . '|exists:page,page_id,status,' . config('cms.backend.status.active'),
            'page_content' => 'required',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'page_title.required' => trans('validation.page.page_title.required'),
            'page_title.max' => trans('validation.page.page_title.maxlength'),
            'page_code.required' => trans('validation.page.page_code.required'),
            'page_code.regex' => trans('validation.page.page_code.code'),
            'page_code.max' => trans('validation.page.page_code.maxlength'),
            'page_code.unique' => trans('validation.page.page_code.unique'),
            'parent_id.required' => trans('validation.page.parent_id.required'),
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid'),
            'page_source.required_unless' => trans('validation.page.page_source.required'),
            'page_source.exists' => trans('validation.page.page_source.not_exist'),
            'page_content.required' => trans('validation.page.page_content.required'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessPage = Globals::getBusiness('Page');
        $businessLangMap = Globals::getBusiness('LangMap');

        $params = [
            'page_title' => clean($request->page_title, 'notags'),
            'page_code' => clean($request->page_code, 'notags'),
            'page_content' => $request->page_content,
            'page_seo_title' => clean($request->page_seo_title, 'notags'),
            'page_seo_keywords' => clean($request->page_seo_keywords, 'notags'),
            'page_seo_description' => clean($request->page_seo_description, 'notags'),
            'parent_id' => $request->parent_id,
            'language_id' => $request->language_id,
            'status' => $request->status
        ];
        $pageInfo = $businessPage->create($params);

        //insert into table langmap
        $page_source = $request->page_source ?? null;
        $params = [
            'item_module' => 'page',
            'item_id' => $pageInfo->page_id,
            'language_id' => $pageInfo->language_id,
            'source_item_id' => $pageInfo->page_id,
            'source_language_id' => null,
        ];
        if (!empty($page_source)) {
            $pageSourceInfo = $businessPage->find($page_source);

            $params['source_item_id'] = $pageSourceInfo->page_id;
            $params['source_language_id'] = $pageSourceInfo->language_id;

            $pageInfo->update([
                'page_code' => $pageSourceInfo->page_code
            ]);
        }

        $businessLangMap->updateOrCreate([
            'item_module' => 'page',
            'item_id' => $pageInfo->page_id,
            'language_id' => $pageInfo->language_id
        ], $params);

        $item_id = $request->item_id ?? null;
        if (!empty($item_id)) {
            $itemInfo = $businessPage->find($item_id);

            if ($itemInfo) {
                $businessLangMap->updateOrCreate([
                    'item_module' => 'page',
                    'item_id' => $itemInfo->page_id,
                    'language_id' => $itemInfo->language_id
                ], [
                    'item_module' => 'page',
                    'item_id' => $itemInfo->page_id,
                    'language_id' => $itemInfo->language_id,
                    'source_item_id' => $pageInfo->page_id,
                    'source_language_id' => $pageInfo->language_id,
                ]);
            }
        }

        flash()->success(trans('common.messages.page.created'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.configwebsite.page.edit', [$pageInfo->page_id]));
        }

        return redirect(route('backend.configwebsite.page.index', ['language_id' => $request->language_id]));
    }

    public function edit(Business_Page $pageInfo)
    {
        //get model
        $businessLangMap = Globals::getBusiness('LangMap');

        $arrLanguage = config('laravellocalization.supportedLocales');

        $pageSource = $businessLangMap->findByAttributes([
            'item_module' => 'page',
            'item_id' => $pageInfo->page_id,
            'language_id' => $pageInfo->language_id
        ]);

        return view('backend.configwebsite.page.edit', compact('arrLanguage', 'pageInfo', 'pageSource'));
    }

    public function update(Request $request, Business_Page $pageInfo)
    {
        $this->validate($request, [
            'page_title' => 'required|max:200',
            'page_code' => 'required|regex:[^[a-z0-9\-]+$]|max:200|unique:page,page_code,' . $pageInfo->page_id . ',page_id,deleted,0,language_id,' . $request->language_id,
            'parent_id' => 'required',
            'language_id' => 'required|in:' . implode(',', array_keys(config('laravellocalization.supportedLocales'))),
            'page_source' => 'required_unless:language_id,' . config('app.locale') . '|exists:page,page_id,status,' . config('cms.backend.status.active'),
            'page_content' => 'required',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'page_title.required' => trans('validation.page.page_title.required'),
            'page_title.max' => trans('validation.page.page_title.maxlength'),
            'page_code.required' => trans('validation.page.page_code.required'),
            'page_code.regex' => trans('validation.page.page_code.code'),
            'page_code.max' => trans('validation.page.page_code.maxlength'),
            'page_code.unique' => trans('validation.page.page_code.unique'),
            'parent_id.required' => trans('validation.page.parent_id.required'),
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid'),
            'page_source.required_unless' => trans('validation.page.page_source.required'),
            'page_source.exists' => trans('validation.page.page_source.not_exist'),
            'page_content.required' => trans('validation.page.page_content.required'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessPage = Globals::getBusiness('Page');
        $businessLangMap = Globals::getBusiness('LangMap');

        //save old language
        $old_language_id = $pageInfo->language_id;

        $params = [
            'page_title' => clean($request->page_title, 'notags'),
            'page_code' => clean($request->page_code, 'notags'),
            'page_content' => $request->page_content,
            'page_seo_title' => clean($request->page_seo_title, 'notags'),
            'page_seo_keywords' => clean($request->page_seo_keywords, 'notags'),
            'page_seo_description' => clean($request->page_seo_description, 'notags'),
            'parent_id' => $request->parent_id,
            'language_id' => $request->language_id,
            'status' => $request->status
        ];
        $pageInfo->update($params);

        //insert into table langmap
        $page_source = $request->page_source ?? null;
        $params = [
            'item_module' => 'page',
            'item_id' => $pageInfo->page_id,
            'language_id' => $pageInfo->language_id,
            'source_item_id' => $pageInfo->page_id,
            'source_language_id' => null,
        ];
        if (!empty($page_source)) {
            $pageSourceInfo = $businessPage->find($page_source);

            $params['source_item_id'] = $pageSourceInfo->page_id;
            $params['source_language_id'] = $pageSourceInfo->language_id;

            $pageInfo->update([
                'page_code' => $pageSourceInfo->page_code
            ]);
        }

        $businessLangMap->updateOrCreate([
            'item_module' => 'page',
            'item_id' => $pageInfo->page_id,
            'language_id' => $old_language_id
        ], $params);

        flash()->success(trans('common.messages.page.updated'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.configwebsite.page.edit', [$pageInfo->page_id]));
        }

        return redirect(route('backend.configwebsite.page.index', ['language_id' => $request->language_id]));
    }
}
