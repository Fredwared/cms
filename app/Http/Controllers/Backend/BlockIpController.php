<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use App\Models\Entity\Business\Business_BlockIp;

class BlockIpController extends BackendController
{
    public function index(Request $request)
    {
        $item = check_paging($request->item);
        $page = $request->page ?? 1;
        $status = $request->status ?? null;

        //get model
        $businessBlockIp = Globals::getBusiness('BlockIp');

        // get list block ip active
        $params = [
            'status' => $status,
            'item' => $item,
            'page' => $page
        ];
        $arrListBlockIp = $businessBlockIp->getListBlockIp($params);

        if ($arrListBlockIp->total() > 0) {
            $maxPage = ceil($arrListBlockIp->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.blockip.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListBlockIp->appends($params)->links();
        
        return view('backend.blockip.index', compact('arrListBlockIp', 'pagination', 'item', 'status'));
    }

    public function create()
    {
        return view('backend.blockip.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ip_address' => 'required|ip|max:50|unique:blockip,ip_address,null,id,deleted,0',
            'ip_description.*' => 'max:100',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'ip_address.required' => trans('validation.blockip.ip_address.required'),
            'ip_address.max' => trans('validation.blockip.ip_address.invalid'),
            'ip_address.max' => trans('validation.blockip.ip_address.maxlength'),
            'ip_address.unique' => trans('validation.blockip.ip_address.unique'),
            'ip_description.*.max' => trans('validation.blockip.ip_description.maxlength'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessBlockIp = Globals::getBusiness('BlockIp');
        
        $params = [
            'ip_address' => $request->ip_address,
            'ip_description' => json_encode($request->ip_description),
            'status' => $request->status
        ];
        $blockipInfo = $businessBlockIp->create($params);

        flash()->success(trans('common.messages.blockip.created'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.blockip.edit', [$blockipInfo->id]));
        }

        return redirect(route('backend.blockip.index'));
    }

    public function edit(Business_BlockIp $blockipInfo)
    {
        return view('backend.blockip.edit', compact('blockipInfo'));
    }

    public function update(Request $request, Business_BlockIp $blockipInfo)
    {
        $this->validate($request, [
            'ip_address' => 'required|ip|max:50|unique:blockip,ip_address,' . $request->ip_address . ',ip_address,deleted,0',
            'ip_description.*' => 'max:100',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'ip_address.required' => trans('validation.blockip.ip_address.required'),
            'ip_address.max' => trans('validation.blockip.ip_address.invalid'),
            'ip_address.max' => trans('validation.blockip.ip_address.maxlength'),
            'ip_address.unique' => trans('validation.blockip.ip_address.unique'),
            'ip_description.*.max' => trans('validation.blockip.ip_description.maxlength'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);
        
        $params = [
            'ip_address' => $request->ip_address,
            'ip_description' => json_encode($request->ip_description),
            'status' => $request->status
        ];
        $blockipInfo->update($params);

        flash()->success(trans('common.messages.blockip.updated'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.blockip.edit', [$blockipInfo->id]));
        }

        return redirect(route('backend.blockip.index'));
    }

    public function destroy(Request $request, Business_BlockIp $blockipInfo)
    {
        $blockipInfo->delete();

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.blockip.deleted'));

            return redirect(route('backend.blockip.index'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.blockip.deleted')]);
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
            $businessBlockIp = Globals::getBusiness('BlockIp');

            foreach ($arrId as $id) {
                $businessBlockIp->find($id)->update([
                    'status' => $status
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success')]);
        } else {
            return response()->json(['error' => 1, 'message' => trans('common.messages.changestatus_error')]);
        }
    }
}
