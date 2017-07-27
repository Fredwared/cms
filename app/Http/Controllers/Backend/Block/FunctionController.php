<?php
namespace App\Http\Controllers\Backend\Block;

use App\Http\Controllers\BackendController;
use App\Models\Entity\Business\Business_Block_Function;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class FunctionController extends BackendController
{
    public function index(Request $request)
    {
        //get model
        $businessBlockFunction = Globals::getBusiness('Block_Function');

        $arrListFunction = $businessBlockFunction->getListFunction();

        return view('backend.block.function.index', compact('arrListFunction'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('backend.block.function.create');
        }

        return redirect(route('backend.block.function.index'));
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.block.function.index'));
        }

        $this->validate($request, [
            'function_name' => 'bail|required|max:100',
            'function_params' => 'bail|required'
        ], [
            'function_name.required' => trans('validation.block.function.function_name.required'),
            'function_name.max' => trans('validation.block.function.function_name.maxlength'),
            'function_params.required' => trans('validation.block.function.function_params.required')
        ]);

        //get model
        $businessBlockFunction = Globals::getBusiness('Block_Function');

        $businessBlockFunction->create([
            'function_name' => $request->function_name,
            'function_params' => $request->function_params,
            'function_type' => $request->function_type
        ]);

        return response()->json(['error' => 0, 'message' => trans('common.messages.block.function.created')]);
    }

    public function edit(Request $request, Business_Block_Function $functionInfo)
    {
        if ($request->ajax()) {
            return view('backend.block.function.edit', compact('functionInfo'));
        }

        return redirect(route('backend.block.function.index'));
    }

    public function update(Request $request, Business_Block_Function $functionInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.block.function.index'));
        }

        $this->validate($request, [
            'function_name' => 'bail|required|max:100',
            'function_params' => 'bail|required'
        ], [
            'function_name.required' => trans('validation.block.function.function_name.required'),
            'function_name.max' => trans('validation.block.function.function_name.maxlength'),
            'function_params.required' => trans('validation.block.function.function_params.required')
        ]);

        $functionInfo->update([
            'function_name' => $request->function_name,
            'function_params' => $request->function_params
        ]);

        return response()->json(['error' => 0, 'message' => trans('common.messages.block.function.updated')]);
    }

    public function destroy(Request $request, Business_Block_Function $functionInfo)
    {
        $functionInfo->delete();

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.block.function.deleted'));

            return redirect(route('backend.block.function.index'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.block.function.deleted')]);
        }
    }
}
