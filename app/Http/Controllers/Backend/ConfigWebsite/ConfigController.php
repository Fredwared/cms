<?php
namespace App\Http\Controllers\Backend\ConfigWebsite;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Config;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class ConfigController extends BackendController
{
    public function index(Request $request)
    {
        $item = check_paging($request->item);
        $page = $request->page ?? 1;
        $field_name = $request->field_name ?? null;
        $field_value = $request->field_value ?? null;

        //get model
        $businessConfig = Globals::getBusiness('Config');

        // get list config;
        $params = [
            'field_name' => $field_name,
            'field_value' => $field_value,
            'item' => $item,
            'page' => $page
        ];
        $arrListConfig = $businessConfig->getListConfig($params);

        if ($arrListConfig->total() > 0) {
            $maxPage = ceil($arrListConfig->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.configwebsite.config.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListConfig->appends($params)->links();

        return view('backend.configwebsite.config.index', compact('arrListConfig', 'pagination', 'item', 'field_name', 'field_value'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('backend.configwebsite.config.create');
        }

        return redirect(route('backend.configwebsite.config.index'));
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.configwebsite.config.index'));
        }

        $this->validate($request, [
            'field_name' => 'bail|required|max:50|unique:config,field_name',
            'field_value.*' => 'bail|required|max:1000'
        ], [
            'field_name.required' => trans('validation.config.field_name.required'),
            'field_name.max' => trans('validation.config.field_name.maxlength'),
            'field_name.unique' => trans('validation.config.field_name.unique'),
            'field_value.*.required' => trans('validation.config.field_value.required'),
            'field_value.*.max' => trans('validation.config.field_value.maxlength')
        ]);

        //get model
        $businessConfig = Globals::getBusiness('Config');

        $params = [
            'field_name' => $request->field_name,
            'field_value' => json_encode($request->field_value)
        ];
        $businessConfig->create($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.config.created')]);
    }

    public function edit(Request $request, Business_Config $configInfo)
    {
        if ($request->ajax()) {
            return view('backend.configwebsite.config.edit', compact('configInfo'));
        }

        return redirect(route('backend.configwebsite.config.index'));
    }

    public function update(Request $request, Business_Config $configInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.configwebsite.config.index'));
        }

        $this->validate($request, [
            'field_name' => 'bail|required|max:50|unique:config,field_name,' . $configInfo->config_id . ',config_id',
            'field_value.*' => 'bail|required|max:1000'
        ], [
            'field_name.required' => trans('validation.config.field_name.required'),
            'field_name.max' => trans('validation.config.field_name.maxlength'),
            'field_name.unique' => trans('validation.config.field_name.unique'),
            'field_value.*.required' => trans('validation.config.field_value.required'),
            'field_value.*.max' => trans('validation.config.field_value.maxlength')
        ]);

        $params = [
            'field_name' => $request->field_name,
            'field_value' => json_encode($request->field_value)
        ];
        $configInfo->update($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.config.updated')]);
    }
}
