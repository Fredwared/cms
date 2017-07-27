<?php
namespace App\Http\Controllers\Backend\Block;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Block_Function;
use App\Models\Entity\Business\Business_Block_Page;
use App\Models\Entity\Business\Business_Block_Template;
use App\Models\Entity\Business\Business_Block_Widget;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class PageController extends BackendController
{
    public function index(Request $request)
    {
        //get model
        $businessBlockPage = Globals::getBusiness('Block_Page');

        //get params
        $language_id = $request->language_id ?? config('app.locale');

        $arrListPage = $businessBlockPage->getListPage($language_id);

        return view('backend.block.page.index', compact('arrListPage', 'language_id'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            //get languages
            $arrLanguage = config('laravellocalization.supportedLocales');

            return view('backend.block.page.create', compact('arrLanguage'));
        }

        return redirect(route('backend.block.page.index'));
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.block.page.index'));
        }

        $this->validate($request, [
            'page_name' => 'bail|required|max:250',
            'page_code' => 'bail|required|max:25|unique:block_page,page_code',
            'page_layout' => 'bail|required',
            'page_url' => 'bail|max:250|url',
            'language_id' => 'required|in:' . implode(',', array_keys(config('laravellocalization.supportedLocales')))
        ], [
            'page_name.required' => trans('validation.block.page.page_name.required'),
            'page_name.max' => trans('validation.block.page.page_name.maxlength'),
            'page_code.required' => trans('validation.block.page.page_code.required'),
            'page_code.max' => trans('validation.block.page.page_code.maxlength'),
            'page_code.unique' => trans('validation.block.page.page_code.unique'),
            'page_layout.required' => trans('validation.block.page.page_layout.required'),
            'page_url.max' => trans('validation.block.page.page_url.maxlength'),
            'page_url.url' => trans('validation.block.page.page_url.invalid'),
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid')
        ]);

        //get model
        $businessBlockPage = Globals::getBusiness('Block_Page');

        $businessBlockPage->create([
            'page_name' => $request->page_name,
            'page_code' => $request->page_code,
            'page_layout' => $request->page_layout,
            'page_url' => $request->page_url
        ]);

        return response()->json(['error' => 0, 'message' => trans('common.messages.block.page.created')]);
    }

    public function edit(Request $request, Business_Block_Page $pageInfo)
    {
        if ($request->ajax()) {
            //get languages
            $arrLanguage = config('laravellocalization.supportedLocales');

            return view('backend.block.page.edit', compact('pageInfo', 'arrLanguage'));
        }

        return redirect(route('backend.block.page.index'));
    }

    public function update(Request $request, Business_Block_Page $pageInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.block.page.index'));
        }

        $this->validate($request, [
            'page_name' => 'bail|required|max:250',
            'page_layout' => 'bail|required',
            'page_url' => 'bail|max:250|url',
            'language_id' => 'required|in:' . implode(',', array_keys(config('laravellocalization.supportedLocales')))
        ], [
            'page_name.required' => trans('validation.block.page.page_name.required'),
            'page_name.max' => trans('validation.block.page.page_name.maxlength'),
            'page_layout.required' => trans('validation.block.page.page_layout.required'),
            'page_url.max' => trans('validation.block.page.page_url.maxlength'),
            'page_url.url' => trans('validation.block.page.page_url.invalid'),
            'language_id.required' => trans('validation.language.required'),
            'language_id.in' => trans('validation.language.invalid')
        ]);

        $pageInfo->update([
            'page_name' => $request->page_name,
            'page_layout' => $request->page_layout,
            'page_url' => $request->page_url
        ]);

        return response()->json(['error' => 0, 'message' => trans('common.messages.block.page.updated')]);
    }

    public function destroy(Request $request, Business_Block_Page $pageInfo)
    {
        $pageInfo->delete();

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.block.page.deleted'));

            return redirect(route('backend.block.page.index'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.block.page.deleted')]);
        }
    }

    public function layout(Request $request, Business_Block_Page $pageInfo)
    {
        //get model
        $businessBlockWidget = Globals::getBusiness('Block_Widget');

        $arrListWidgetTop = $businessBlockWidget->getListWidget([
            'page_code' =>  $pageInfo->page_code,
            'widget_area' => 'top'
        ]);

        $arrListWidgetBottom = $businessBlockWidget->getListWidget([
            'page_code' =>  $pageInfo->page_code,
            'widget_area' => 'bottom'
        ]);

        $arrListWidgetCenter = $businessBlockWidget->getListWidget([
            'page_code' =>  $pageInfo->page_code,
            'widget_area' => 'center'
        ]);

        $arrListWidgetLeft = $businessBlockWidget->getListWidget([
            'page_code' =>  $pageInfo->page_code,
            'widget_area' => 'left'
        ]);

        $arrListWidgetRight = $businessBlockWidget->getListWidget([
            'page_code' =>  $pageInfo->page_code,
            'widget_area' => 'right'
        ]);

        return view('backend.block.page.layout', compact('pageInfo', 'arrListWidgetTop', 'arrListWidgetBottom', 'arrListWidgetCenter', 'arrListWidgetLeft', 'arrListWidgetRight'));
    }

    public function getTemplate(Request $request, Business_Block_Page $pageInfo)
    {
        //get params
        $type = $request->type;
        $area = $request->area;

        //get model
        $businessBlockTemplate = Globals::getBusiness('Block_Template');

        $arrListTemplate = $businessBlockTemplate->getListTemplate([
            'template_area' => $area,
            'template_type' => $type
        ]);

        if (!$arrListTemplate->isEmpty()) {
            $sHTML = view('backend.block.page.partials.template', compact('pageInfo', 'type', 'area', 'arrListTemplate'))->render();

            return response()->json(['error' => 0, 'data' => $sHTML]);
        }

        return response()->json(['error' => 1, 'message' => trans('common.messages.nodata')]);
    }

    public function detailTemplate(Business_Block_Page $pageInfo, Business_Block_Template $templateInfo, Business_Block_Widget $widgetInfo = null)
    {
        $sHTMLTemplate = '';
        if (!empty($templateInfo->template_params)) {
            $arrParams = json_decode($templateInfo->template_params);
            $arrWidgetConfig = $widgetInfo ? json_decode($widgetInfo->widget_config) : [];

            foreach ($arrParams as $key => $value) {
                $value = (empty($arrWidgetConfig) || !isset($arrWidgetConfig->view->{$key})) ? $value : $arrWidgetConfig->view->{$key};
                $title = trans('common.block.page.widget.' . $key);
                $sHTMLTemplate .= '<div class="form-group">'
                    . '<label for="' . $key . '" class="required">' . $title . '</label>'
                    . '<input type="text" class="form-control" name="view[' . $key . ']" value="' . $value . '" placeholder="' . $title . '">'
                . '</div>';
            }
        }

        $sHTMLFunction = '<div class="form-group">'
            . '<label for="function_id" class="required">Function</label>'
            . '<select class="form-control" name="function_id" data-link="' . route('backend.block.page.layout.function.detail', [$pageInfo->page_code, 0, ($widgetInfo ? $widgetInfo->widget_id : null)]) . '">'
                . '<option value="">Chọn function</option>';
                foreach ($templateInfo->functions as $function) {
                    $selected = $widgetInfo ? ($widgetInfo->function_id == $function->function_id ? ' selected="selected"' : '') : '';
                    $sHTMLFunction .= '<option value="' . $function->function_id . '"' . $selected . '>' . $function->function_name . '</option>';
                }
            $sHTMLFunction .= '</select>'
        . '</div>';

        return response()->json(['template' => $sHTMLTemplate, 'function' => $sHTMLFunction]);
    }

    public function detailFunction(Business_Block_Page $pageInfo, Business_Block_Function $functionInfo, Business_Block_Widget $widgetInfo = null)
    {
        //get model
        $businessCategory = Globals::getBusiness('Category');

        // get list category active;
        $arrListCategory = $businessCategory->getListCategory([
            'language_id' => $pageInfo->language_id,
            'status' => config('cms.backend.status.active'),
            'category_type' => config('cms.backend.category.type.' . $functionInfo->function_type)
        ]);

        $arrParams = json_decode($functionInfo->function_params);
        $arrWidgetConfig = $widgetInfo ? json_decode($widgetInfo->widget_config) : [];
        $sHTML = '';

        foreach ($arrParams->params as $key => $value) {
            $value = (empty($arrWidgetConfig) || !isset($arrWidgetConfig->params->{$key})) ? $value : $arrWidgetConfig->params->{$key};
            $title = trans('common.block.page.widget.' . $key);
            $sHTML .= '<div class="form-group"><label for="' . $key . '" class="required">' . $title . '</label>';

                switch ($key) {
                    case 'category_id':
                        $sHTML .= '<select class="form-control" name="params[' . $key . ']"><option value="">Chọn category</option>';
                            foreach ($arrListCategory as $category) {
                                $selected = $value == $category->category_id ? ' selected="selected"' : '';
                                $sHTML .= '<option value="' . $category->category_id . '"' . $selected . '>' . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->category_level - 1) . $category->category_title . '</option >';
                            }
                        $sHTML .= '</select>';
                        break;
                    case 'child':
                        $sHTML .= '<div class="radio">'
                            . '<label class="mr10"><input type="radio" value="1" name="params[' . $key . ']"' . ($value == 1 ? ' checked="checked"' : '') . ' />Có</label>'
                            . '<label><input type="radio" value="0" name="params[' . $key . ']"' . ($value == 0 ? ' checked="checked"' : '') . ' />Không</label>'
                        . '</div>';
                        break;
                    case 'article_type':
                        $sHTML .= '<input type="text" class="form-control" name="params[' . $key . ']" value="' . $value . '" placeholder="' . $title . '">'
                            . '<p class="help-block">0: Tất cả, 1: Bài thường, 2: Bài hình, 3: Bài video, 4: Bài infographic.</p>';
                        break;
                    default:
                        $sHTML .= '<input type="text" class="form-control" name="params[' . $key . ']" value="' . $value . '" placeholder="' . $title . '">';
                        break;
                }

            $sHTML .= '</div>';
        }

        return $sHTML;
    }

    public function storeWidgetStatic(Request $request, Business_Block_Page $pageInfo, $area)
    {
        //get params
        $template_id = $request->template_id;
        $widget_order = $request->widget_order ?? 1;

        //get model
        $businessBlockWidget = Globals::getBusiness('Block_Widget');

        $widgetInfo = $businessBlockWidget->create([
            'widget_order' => $widget_order,
            'widget_area' => $area,
            'widget_type' => 'static',
            'page_code' => $pageInfo->page_code,
            'template_id' => $template_id,
            'status' => config('cms.backend.status.active')
        ]);

        return view('backend.block.page.partials.widget.static', ['widget' => $widgetInfo])->render();
    }

    public function storeWidgetDynamic(Request $request, Business_Block_Page $pageInfo, $area)
    {
        $this->validate($request, [
            'view.*' => 'bail|required',
            'function_id' => 'bail|required',
            'params.*' => 'bail|required'
        ], [
            'view.*.required' => trans('validation.block.widget.required'),
            'function_id.required' => trans('validation.block.widget.required'),
            'params.*.required' => trans('validation.block.widget.required')
        ]);

        //get params
        $template_id = $request->template_id;
        $function_id = $request->function_id;
        $widget_order = $request->widget_order ?? 1;
        $widget_config = [
            'view' => $request->view,
            'params' => $request->params
        ];

        //get model
        $businessBlockWidget = Globals::getBusiness('Block_Widget');

        $widgetInfo = $businessBlockWidget->create([
            'widget_config' => json_encode($widget_config),
            'widget_order' => $widget_order,
            'widget_area' => $area,
            'widget_type' => 'dynamic',
            'page_code' => $pageInfo->page_code,
            'template_id' => $template_id,
            'function_id' => $function_id,
            'status' => config('cms.backend.status.active')
        ]);

        return view('backend.block.page.partials.widget.dynamic', ['widget' => $widgetInfo])->render();
    }

    public function editWidget(Business_Block_Widget $widgetInfo)
    {
        //get model
        $businessBlockPage = Globals::getBusiness('Block_Page');
        $businessBlockTemplate = Globals::getBusiness('Block_Template');

        $pageInfo = $businessBlockPage->find($widgetInfo->page_code);
        $templateInfo = $businessBlockTemplate->find($widgetInfo->template_id);

        return $this->detailTemplate($pageInfo, $templateInfo, $widgetInfo);
    }

    public function updateWidget(Request $request, Business_Block_Widget $widgetInfo)
    {
        $this->validate($request, [
            'view.*' => 'bail|required',
            'function_id' => 'bail|required',
            'params.*' => 'bail|required'
        ], [
            'view.*.required' => trans('validation.block.widget.required'),
            'function_id.required' => trans('validation.block.widget.required'),
            'params.*.required' => trans('validation.block.widget.required')
        ]);

        //get params
        $function_id = $request->function_id;
        $widget_config = [
            'view' => $request->view,
            'params' => $request->params
        ];

        $widgetInfo->update([
            'widget_config' => json_encode($widget_config),
            'function_id' => $function_id
        ]);

        return view('backend.block.page.partials.widget.dynamic', ['widget' => $widgetInfo])->render();
    }

    public function destroyWidget(Business_Block_Widget $widgetInfo)
    {
        $widgetInfo->delete();

        return response()->json(['error' => 0, 'message' => trans('common.messages.block.widget.deleted')]);
    }

    public function changeStatusWidget(Business_Block_Widget $widgetInfo)
    {
        $widgetInfo->update([
            'status' => $widgetInfo->status == 1 ? 2 : 1
        ]);

        return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success'), 'data' => view('backend.block.page.partials.widget.' . $widgetInfo->widget_type, ['widget' => $widgetInfo])->render()]);
    }

    public function updateSortWidget(Request $request)
    {
        //get model
        $businessBlockWidget = Globals::getBusiness('Block_Widget');

        $arrId = $request->id ?? [];
        if (!empty($arrId)) {
            foreach ($arrId as $index => $id) {
                $businessBlockWidget->find($id)->update([
                    'widget_order' => $index + 1
                ]);
            }
        }

        return response()->json(['error' => 0, 'message' => trans('common.messages.sort_success')]);
    }
}
