<?php
namespace App\Http\Controllers\Backend\ConfigWebsite;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use App\Models\Entity\Business\Business_Translate;

class TranslateController extends BackendController
{
    public function index(Request $request)
    {
        $item = check_paging($request->item);
        $page = $request->page ?? 1;
        $translate_code = $request->translate_code ?? null;
        $translate_mode = $request->translate_mode ?? null;
        $translate_content = $request->translate_content ?? null;

        //get model
        $businessTranslate = Globals::getBusiness('Translate');

        // get list config;
        $params = [
            'translate_code' => $translate_code,
            'translate_mode' => $translate_mode,
            'translate_content' => $translate_content,
            'item' => $item,
            'page' => $page
        ];
        $arrListTranslate = $businessTranslate->getListTranslate($params);

        if ($arrListTranslate->total() > 0) {
            $maxPage = ceil($arrListTranslate->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.configwebsite.translate.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListTranslate->appends($params)->links();

        return view('backend.configwebsite.translate.index', compact('arrListTranslate', 'pagination', 'item', 'translate_code', 'translate_mode', 'translate_content'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('backend.configwebsite.translate.create');
        }

        return redirect(route('backend.configwebsite.translate.index'));
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.configwebsite.translate.index'));
        }

        $this->validate($request, [
            'translate_code' => 'bail|required|max:50|unique:translate,translate_code',
            'translate_mode' => 'bail|required',
            'translate_content.*' => 'bail|required|max:5000',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'translate_code.required' => trans('validation.translate.translate_code.required'),
            'translate_code.max' => trans('validation.translate.translate_code.maxlength'),
            'translate_code.unique' => trans('validation.translate.translate_code.unique'),
            'translate_mode.required' => trans('validation.translate.translate_mode.required'),
            'translate_content.*.required' => trans('validation.translate.translate_content.required'),
            'translate_content.*.max' => trans('validation.translate.translate_content.maxlength'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessTranslate = Globals::getBusiness('Translate');

        $params = [
            'translate_code' => $request->translate_code,
            'translate_mode' => $request->translate_mode,
            'translate_content' => json_encode($request->translate_content),
            'status' => $request->status
        ];
        $businessTranslate->create($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.translate.created')]);
    }

    public function edit(Request $request, Business_Translate $translateInfo)
    {
        if ($request->ajax()) {
            return view('backend.configwebsite.translate.edit', compact('translateInfo'));
        }

        return redirect(route('backend.configwebsite.translate.index'));
    }

    public function update(Request $request, Business_Translate $translateInfo)
    {
        if (!$request->ajax()) {
            return redirect(route('backend.configwebsite.translate.index'));
        }

        $this->validate($request, [
            'translate_code' => 'bail|required|max:50|unique:translate,translate_code,' . $translateInfo->translate_id . ',translate_id',
            'translate_mode' => 'bail|required',
            'translate_content.*' => 'bail|required|max:5000',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'translate_code.required' => trans('validation.translate.translate_code.required'),
            'translate_code.max' => trans('validation.translate.translate_code.maxlength'),
            'translate_code.unique' => trans('validation.translate.translate_code.unique'),
            'translate_mode.required' => trans('validation.translate.translate_mode.required'),
            'translate_content.*.required' => trans('validation.translate.translate_content.required'),
            'translate_content.*.max' => trans('validation.translate.translate_content.maxlength'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        $params = [
            'translate_code' => $request->translate_code,
            'translate_mode' => $request->translate_mode,
            'translate_content' => json_encode($request->translate_content),
            'status' => $request->status
        ];
        $translateInfo->update($params);

        return response()->json(['error' => 0, 'message' => trans('common.messages.translate.updated')]);
    }

    public function destroy(Request $request, Business_Translate $translateInfo)
    {
        $translateInfo->delete();

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.translate.deleted'));

            return redirect(route('backend.configwebsite.translate.index'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.translate.deleted')]);
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
            $businessTranslate = Globals::getBusiness('Translate');

            foreach ($arrId as $id) {
                $translateInfo = $businessTranslate->find($id);
                $translateInfo->update([
                    'status' => $status
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success')]);
        } else {
            return response()->json(['error' => 1, 'message' => trans('common.messages.changestatus_error')]);
        }
    }
}
