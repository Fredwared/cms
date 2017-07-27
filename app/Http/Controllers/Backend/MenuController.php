<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;
use App\Models\Entity\Business\Business_Menu;

class MenuController extends BackendController
{
    public function index(Request $request)
    {
        $status = $request->status ?? null;
        
        //get model
        $businessMenu = Globals::getBusiness('Menu');

        // get list menu active;
        $arrListMenu = $businessMenu->getListMenu([
            'status' => $status
        ]);
        
        return view('backend.menu.index', compact('arrListMenu', 'status'));
    }

    public function create()
    {
        //get model
        $businessMenu = Globals::getBusiness('Menu');

        // get list menu active and parent_id = 0;
        $arrListMenu = $businessMenu->getListMenu([
            'parent_id' => 0,
            'status' => config('cms.backend.status.active'),
        ]);

        $arrTreeMenu = $businessMenu->getListMenu([
            'status' => config('cms.backend.status.active'),
        ]);
        
        return view('backend.menu.create', compact('arrListMenu', 'arrTreeMenu'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'menu_name.*' => 'required|max:100',
            'menu_code' => 'unique:menu,menu_code|max:200',
            'parent_id' => 'required',
            'route_name' => 'max:200',
            'menu_icon' => 'max:200',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status'))),
            'display_order' => 'integer'
        ], [
            'menu_name.*.required' => trans('validation.menu.menu_name.required'),
            'menu_name.*.max' => trans('validation.menu.menu_name.maxlength'),
            'menu_code.unique' => trans('validation.menu.menu_code.unique'),
            'menu_code.max' => trans('validation.menu.menu_code.maxlength'),
            'parent_id.required' => trans('validation.menu.parent_id.required'),
            'parent_id.exists' => trans('validation.menu.parent_id.not_exist'),
            'route_name.max' => trans('validation.menu.route_name.maxlength'),
            'menu_icon.max' => trans('validation.menu.menu_icon.maxlength'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid'),
            'display_order.integer' => trans('validation.display_order.number')
        ]);

        //get model
        $businessMenu = Globals::getBusiness('Menu');
        $businessProfile = Globals::getBusiness('Profile');
        
        $display_order = $request->display_order;
        if (empty($display_order)) {
            $display_order = $businessMenu->getLastOrder($request->parent_id);
        }
        $params = [
            'menu_name' => json_encode($request->menu_name),
            'menu_code' => $request->menu_code ?? null,
            'route_name' => $request->route_name ?? null,
            'menu_icon' => $request->menu_icon ?? 'fa-hand-o-right',
            'parent_id' => $request->parent_id,
            'status' => $request->status,
            'display_order' => $display_order
        ];
        $menuInfo = $businessMenu->create($params);
        
        //add table profile
        $businessProfile->addProfileAdminByMenu($menuInfo->menu_id);

        flash()->success(trans('common.messages.menu.created'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.menu.edit', [$menuInfo->menu_id]));
        }

        return redirect(route('backend.menu.index'));
    }

    public function edit(Business_Menu $menuInfo)
    {
        //get model
        $businessMenu = Globals::getBusiness('Menu');

        // get list menu active and parent_id = 0;
        $arrListMenu = $businessMenu->getListMenu([
            'parent_id' => 0,
            'status' => config('cms.backend.status.active'),
        ]);

        $arrTreeMenu = $businessMenu->getListMenu([
            'status' => config('cms.backend.status.active'),
        ]);
        
        return view('backend.menu.edit', compact('arrListMenu', 'arrTreeMenu', 'menuInfo'));
    }

    public function update(Request $request, Business_Menu $menuInfo)
    {
        $this->validate($request, [
            'menu_name.*' => 'required|max:100',
            'menu_code' => 'unique:menu,menu_code,' . $menuInfo->menu_code . ',menu_code|max:200',
            'parent_id' => 'required',
            'route_name' => 'max:200',
            'menu_icon' => 'max:200',
            'status' => 'required|in:' . implode(',', array_values(config('cms.backend.status'))),
            'display_order' => 'integer'
        ], [
            'menu_name.*.required' => trans('validation.menu.menu_name.required'),
            'menu_name.*.max' => trans('validation.menu.menu_name.maxlength'),
            'menu_code.unique' => trans('validation.menu.menu_code.unique'),
            'menu_code.max' => trans('validation.menu.menu_code.maxlength'),
            'parent_id.required' => trans('validation.menu.parent_id.required'),
            'parent_id.exists' => trans('validation.menu.parent_id.not_exist'),
            'route_name.max' => trans('validation.menu.route_name.maxlength'),
            'menu_icon.max' => trans('validation.menu.menu_icon.maxlength'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid'),
            'display_order.integer' => trans('validation.display_order.number')
        ]);
        
        $display_order = $request->display_order;
        if (empty($display_order)) {
            $display_order = $menuInfo->display_order;
        }
        $params = [
            'menu_name' => json_encode($request->menu_name),
            'menu_code' => $request->menu_code ?? null,
            'route_name' => $request->route_name ?? null,
            'menu_icon' => $request->menu_icon ?? 'fa-hand-o-right',
            'parent_id' => $request->parent_id,
            'status' => $request->status,
            'display_order' => $display_order
        ];
        $menuInfo->update($params);

        flash()->success(trans('common.messages.menu.updated'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.menu.edit', [$menuInfo->menu_id]));
        }

        return redirect(route('backend.menu.index'));
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
            $businessMenu = Globals::getBusiness('Menu');

            foreach ($arrId as $id) {
                $businessMenu->find($id)->update([
                    'status' => $status
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success')]);
        } else {
            return response()->json(['error' => 1, 'message' => trans('common.messages.changestatus_error')]);
        }
    }

    public function sort(Request $request)
    {
        //get model
        $businessMenu = Globals::getBusiness('Menu');

        if ($request->isMethod('get')) {
            // get list menu;
            $arrListMenu = $businessMenu->getListMenu([
                'parent_id' => 0
            ]);

            return view('backend.menu.sort', compact('arrListMenu'));
        } else {
            foreach ($request->menu[0] as $parent_order => $parent_id) {
                $businessMenu->find($parent_id)->update([
                    'display_order' => $parent_order + 1
                ]);

                if (isset($request->menu[$parent_id])) {
                    foreach ($request->menu[$parent_id] as $child_order => $child_id) {
                        $businessMenu->find($child_id)->update([
                            'display_order' => $child_order + 1
                        ]);
                    }
                }
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.sort_success')]);
        }
    }
}
