<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use App\Models\Entity\Business\Business_Role;
use Illuminate\Support\Facades\Route;

class RoleController extends BackendController
{
    public function index(Request $request)
    {
        $status = $request->status ?? null;

        //get model
        $businessRole = Globals::getBusiness('Role');

        // get list role active
        $arrListRole = $businessRole->getListRole($status);
        
        return view('backend.role.index', compact('arrListRole', 'status'));
    }

    public function create()
    {
        $actions = $this->getListAction();

        return view('backend.role.create', compact('actions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'role_code' => 'required|max:10|unique:role,role_code',
            'role_name.*' => 'required|max:100',
            'role_priority' => 'integer',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status')))
        ], [
            'role_code.required' => trans('validation.role.role_code.required'),
            'role_code.max' => trans('validation.role.role_code.maxlength'),
            'role_code.unique' => trans('validation.role.role_code.unique'),
            'role_name.*.required' => trans('validation.role.role_name.required'),
            'role_name.*.max' => trans('validation.role.role_name.maxlength'),
            'role_priority.integer' => trans('validation.role.role_priority.number'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessRole = Globals::getBusiness('Role');
        $businessProfile = Globals::getBusiness('Profile');
        
        $params = [
            'role_code' => strtolower($request->role_code),
            'role_name' => json_encode($request->role_name),
            'role_priority' => $request->role_priority ?? 1,
            'action_applied' => $request->action_applied ? implode(',', $request->action_applied) : '',
            'status' => $request->status
        ];
        $roleInfo = $businessRole->insert($params);
        
        //add table profile
        $businessProfile->addProfileAdminByRole($roleInfo->role_code);

        flash()->success(trans('common.messages.role.created'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.role.edit', [$roleInfo->role_code]));
        }

        return redirect(route('backend.role.index'));
    }

    public function edit(Business_Role $roleInfo)
    {
        $actions = $this->getListAction();

        return view('backend.role.edit', compact('roleInfo', 'actions'));
    }

    public function update(Request $request, Business_Role $roleInfo)
    {
        $this->validate($request, [
            'role_name.*' => 'required|max:100',
            'role_priority' => 'integer'
        ], [
            'role_name.*.required' => trans('validation.role.role_name.required'),
            'role_name.*.max' => trans('validation.role.role_name.maxlength'),
            'role_priority.integer' => trans('validation.role.role_priority.number')
        ]);

        $params = [
            'role_name' => json_encode($request->role_name),
            'role_priority' => $request->role_priority ?? 1,
            'action_applied' => $request->action_applied ? implode(',', $request->action_applied) : ''
        ];
        $roleInfo->update($params);

        flash()->success(trans('common.messages.role.updated'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.role.edit', [$roleInfo->role_code]));
        }

        return redirect(route('backend.role.index'));
    }

    public function destroy(Request $request, Business_Role $roleInfo)
    {
        if ($roleInfo->profiles()->count() > 0) {
            return redirect('404');
        }

        $roleInfo->delete();

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.role.deleted'));

            return redirect(route('backend.role.index'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.role.deleted')]);
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
            $businessRole = Globals::getBusiness('Role');

            foreach ($arrId as $id) {
                $roleInfo = $businessRole->find($id);
                $roleInfo->update([
                    'status' => $roleInfo->profiles()->count() > 0 ? $roleInfo->status : $status
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success')]);
        } else {
            return response()->json(['error' => 1, 'message' => trans('common.messages.changestatus_error')]);
        }
    }

    private function getListAction()
    {
        $actions = [];

        foreach (Route::getRoutes() as $route) {
            $action = $route->getAction();

            if (!isset($action['controller']) || !starts_with($action['controller'], 'App\Http\Controllers\Backend')) {
                continue;
            }

            $controller = class_basename($action['controller']);
            $namespace = strtolower(class_basename($action['namespace']));

            if ($namespace == 'auth' || $namespace == 'utils') {
                continue;
            }

            list($controller, $action) = explode('@', $controller);

            $actions[] = $action;
        }

        return array_unique($actions);
    }
}
