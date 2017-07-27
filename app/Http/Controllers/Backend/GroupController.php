<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use App\Models\Entity\Business\Business_Group;

class GroupController extends BackendController
{
    public function index(Request $request)
    {
        $item = check_paging($request->item);
        $page = $request->page ?? 1;
        $status = $request->status ?? null;

        //get model
        $businessGroup = Globals::getBusiness('Group');

        // get list group;
        $params = [
            'status' => $status,
            'item' => $item,
            'page' => $page
        ];
        $arrListGroup = $businessGroup->getListGroup($params);

        if ($arrListGroup->total() > 0) {
            $maxPage = ceil($arrListGroup->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.group.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListGroup->appends($params)->links();
        
        return view('backend.group.index', compact('arrListGroup', 'pagination', 'item', 'status'));
    }

    public function create()
    {
        return view('backend.group.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'group_name' => 'required|max:50',
            'group_description' => 'max:200',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'group_name.required' => trans('validation.group.group_name.required'),
            'group_name.max' => trans('validation.group.group_name.maxlength'),
            'group_description.max' => trans('validation.group.group_description.maxlength'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessGroup = Globals::getBusiness('Group');
        
        $params = [
            'group_name' => $request->group_name,
            'group_description' => $request->group_description ?? null,
            'status' => $request->status
        ];
        $groupInfo = $businessGroup->create($params);

        flash()->success(trans('common.messages.group.created'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.group.edit', [$groupInfo->id]));
        }

        return redirect(route('backend.group.index'));
    }

    public function edit(Business_Group $groupInfo)
    {
        if ($groupInfo->group_id == config('cms.backend.super_admin_group_id') && auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            return redirect('404');
        }

        //get model
        $businessMenu = Globals::getBusiness('Menu');
        $businessRole = Globals::getBusiness('Role');
        
        // get list menu active;
        $arrListMenu = $businessMenu->getListMenu([
            'status' => config('cms.backend.status.active')
        ]);
        
        // get list role active
        $arrListRole = $businessRole->getListRole(1);
        
        return view('backend.group.edit', compact('groupInfo', 'arrListMenu', 'arrListRole'));
    }

    public function update(Request $request, Business_Group $groupInfo)
    {
        if ($groupInfo->group_id == config('cms.backend.super_admin_group_id') && auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            return redirect('404');
        }
        
        $this->validate($request, [
            'group_name' => 'required|max:50',
            'group_description' => 'max:200',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'group_name.required' => trans('validation.group.group_name.required'),
            'group_name.max' => trans('validation.group.group_name.maxlength'),
            'group_description.max' => trans('validation.group.group_description.maxlength'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        $params = [
            'group_name' => $request->group_name,
            'group_description' => $request->group_description ?? null,
            'status' => $request->status
        ];
        $groupInfo->update($params);

        //get model
        $businessGroupUser = Globals::getBusiness('Group_User');
        $businessProfile = Globals::getBusiness('Profile');

        //update table group_user
        $businessGroupUser->addGroupUserByGroup($groupInfo->group_id, $request->users ?? []);

        //update table profile
        $businessProfile->addProfileByGroup($groupInfo->group_id, $request->profile ?? []);

        flash()->success(trans('common.messages.group.updated'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.group.edit', [$groupInfo->group_id]));
        }

        return redirect(route('backend.group.index'));
    }

    public function profile(Business_Group $groupInfo)
    {
        if ($groupInfo->group_id == config('cms.backend.super_admin_group_id') && auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            return redirect('404');
        }

        //get model
        $businessMenu = Globals::getBusiness('Menu');
        $businessRole = Globals::getBusiness('Role');
        
        // get list menu active;
        $arrListMenu = $businessMenu->getListMenu([
            'status' => config('cms.backend.status.active')
        ]);

        // get list role active
        $arrListRole = $businessRole->getListRole(1);

        return view('backend.group.profile', compact('groupInfo', 'arrListMenu', 'arrListRole'));
    }

    public function destroy(Request $request, Business_Group $groupInfo)
    {
        if ($groupInfo->group_id == config('cms.backend.super_admin_group_id') && auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            return redirect('404');
        }

        if ($groupInfo->users()->count() > 0 || $groupInfo->profiles()->count() > 0) {
            return redirect('404');
        }
        
        $groupInfo->delete();

        //get model
        $businessProfile = Globals::getBusiness('Profile');
        
        $businessProfile->forceDeleteByAttributes([
            'group_id' => $groupInfo->group_id
        ]);

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.group.deleted'));

            return redirect(route('backend.group.index'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.group.deleted')]);
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
            $businessGroup = Globals::getBusiness('Group');

            foreach ($arrId as $id) {
                $groupInfo = $businessGroup->find($id);
                $groupInfo->update([
                    'status' => $groupInfo->users()->count() ? $groupInfo->status  : $status
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success')]);
        } else {
            return response()->json(['error' => 1, 'message' => trans('common.messages.changestatus_error')]);
        }
    }
}
