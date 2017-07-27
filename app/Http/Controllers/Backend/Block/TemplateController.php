<?php
namespace App\Http\Controllers\Backend\Block;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Block_Template;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends BackendController
{
    public function index(Request $request)
    {
        //get model
        $businessBlockTemplate = Globals::getBusiness('Block_Template');

        $arrListTemplate = $businessBlockTemplate->getListTemplate();

        return view('backend.block.template.index', compact('arrListTemplate'));
    }

    public function create(Request $request, $type)
    {
        if ($request->ajax()) {
            if (!in_array($type, config('cms.backend.block.type'))) {
                $type = array_first(config('cms.backend.block.type'));
            }

            //get model
            $businessBlockFunction = Globals::getBusiness('Block_Function');

            $arrListFunction = $businessBlockFunction->getListFunction();

            return view('backend.block.template.create_' . $type, compact('type', 'arrListFunction'));
        }

        return redirect(route('backend.block.template.index'));
    }

    public function store(Request $request, $type)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.block.template.index'));
        }

        $rules = [
            'template_name' => 'bail|required|max:100',
            'template_area' => 'bail|required',
            'template_thumbnail' => 'bail|required'
        ];
        if ($type == 'static') {
            $rules['template_content'] = 'bail|required';
        } else {
            $rules['template_view'] = 'bail|required|max:200';
        }

        $this->validate($request, $rules, [
            'template_name.required' => trans('validation.block.template.template_name.required'),
            'template_name.max' => trans('validation.block.template.template_name.maxlength'),
            'template_area.required' => trans('validation.block.template.template_area.required'),
            'template_thumbnail.required' => trans('validation.block.template.template_thumbnail.required'),
            'template_content.required' => trans('validation.block.template.template_content.required'),
            'template_view.required' => trans('validation.block.template.template_view.required'),
            'template_view.max' => trans('validation.block.template.template_view.maxlength')
        ]);

        if (!empty($request->template_thumbnail) && !str_contains($request->template_thumbnail, 'block/')) {
            $old_thumbnail = $request->template_thumbnail;
            $old_file = config('cms.backend.media.path') . '/tmp/' . $old_thumbnail;

            $new_thumbnail = preg_replace('/^(.[^.]*)\.([a-z]+)$/', 'block/$1-' . rand(1111, 9999) . '-' . time() . '.$2', $old_thumbnail);
            $new_file = config('cms.backend.media.path') . '/' . config('cms.backend.media.name.1') . '/' . $new_thumbnail;

            // Get disk storage
            $disk = Storage::disk(config('cms.backend.media.storage'));

            $disk->move($old_file, $new_file);
            $disk->delete($old_file);
        }

        //get model
        $businessBlockTemplate = Globals::getBusiness('Block_Template');
        $businessBlockTemplateFunction = Globals::getBusiness('Block_Template_Function');

        $templateInfo = $businessBlockTemplate->create([
            'template_name' => $request->template_name,
            'template_content' => $request->template_content ?? null,
            'template_view' => $request->template_view ?? null,
            'template_thumbnail' => $new_thumbnail,
            'template_params' => $request->template_params ?? null,
            'template_area' => $request->template_area,
            'template_type' => $type
        ]);

        $businessBlockTemplateFunction->addByTemplate($templateInfo->template_id, $request->template_function ?? []);

        return response()->json(['error' => 0, 'message' => trans('common.messages.block.template.created')]);
    }

    public function edit(Request $request, Business_Block_Template $templateInfo)
    {
        if ($request->ajax()) {
            //get model
            $businessBlockFunction = Globals::getBusiness('Block_Function');

            $arrTemplateFunction = $templateInfo->functions->pluck('function_id')->toArray();

            $arrListFunction = $businessBlockFunction->getListFunction();

            return view('backend.block.template.edit_' . $templateInfo->template_type, compact('templateInfo', 'arrTemplateFunction', 'arrListFunction'));
        }

        return redirect(route('backend.block.template.index'));
    }

    public function update(Request $request, Business_Block_Template $templateInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.block.template.index'));
        }

        $rules = [
            'template_name' => 'bail|required|max:100',
            'template_area' => 'bail|required',
            'template_thumbnail' => 'bail|required'
        ];
        if ($templateInfo->template_type == 'static') {
            $rules['template_content'] = 'bail|required';
        } else {
            $rules['template_view'] = 'bail|required|max:200';
        }

        $this->validate($request, $rules, [
            'template_name.required' => trans('validation.block.template.template_name.required'),
            'template_name.max' => trans('validation.block.template.template_name.maxlength'),
            'template_area.required' => trans('validation.block.template.template_area.required'),
            'template_thumbnail.required' => trans('validation.block.template.template_thumbnail.required'),
            'template_content.required' => trans('validation.block.template.template_content.required'),
            'template_view.required' => trans('validation.block.template.template_view.required'),
            'template_view.max' => trans('validation.block.template.template_view.maxlength')
        ]);

        if (!str_contains($request->template_thumbnail, 'block/')) {
            $old_thumbnail = $request->template_thumbnail;
            $old_file = config('cms.backend.media.path') . '/tmp/' . $old_thumbnail;

            $new_thumbnail = preg_replace('/^(.[^.]*)\.([a-z]+)$/', 'block/$1-' . rand(1111, 9999) . '-' . time() . '.$2', $old_thumbnail);
            $new_file = config('cms.backend.media.path') . '/' . config('cms.backend.media.name.1') . '/' . $new_thumbnail;

            // Get disk storage
            $disk = Storage::disk(config('cms.backend.media.storage'));

            $disk->move($old_file, $new_file);
            $disk->delete($old_file);
        } else {
            $new_thumbnail = $templateInfo->template_thumbnail;
        }

        $templateInfo->update([
            'template_name' => $request->template_name,
            'template_content' => $request->template_content ?? $templateInfo->template_content,
            'template_view' => $request->template_view ?? $templateInfo->template_view,
            'template_thumbnail' => $new_thumbnail,
            'template_params' => $request->template_params ?? $templateInfo->template_params,
            'template_area' => $request->template_area
        ]);

        //get model
        $businessBlockTemplateFunction = Globals::getBusiness('Block_Template_Function');

        $businessBlockTemplateFunction->addByTemplate($templateInfo->template_id, $request->template_function ?? []);

        return response()->json(['error' => 0, 'message' => trans('common.messages.block.template.updated')]);
    }

    public function destroy(Request $request, Business_Block_Template $templateInfo)
    {
        $templateInfo->delete();

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.block.template.deleted'));

            return redirect(route('backend.block.template.index'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.block.template.deleted')]);
        }
    }
}
