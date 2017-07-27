<?php
namespace App\Models\Entity\Business\Traits\Behavior;

use App\Models\Services\Globals;

trait ProfileBehavior
{
    public function addProfileByGroup($intGroupId, $arrProfile = [])
    {
        $this->forceDeleteByAttributes(['group_id' => $intGroupId]);

        if (!empty($arrProfile)) {
            foreach ($arrProfile as $intMenuId => $arrRoleCode) {
                foreach ($arrRoleCode as $roleCode => $intStatus) {
                    $this->create([
                        'group_id' => $intGroupId,
                        'menu_id' => $intMenuId,
                        'role_code' => $roleCode
                    ]);
                }
            }
        }
    }

    public function checkPermission($menu, $role, $groups)
    {
        $groups = (array)$groups;
        $permission = false;

        foreach ($groups as $group) {
            $profile = $this->findByAttributes([
                'group_id' => $group,
                'menu_id' => $menu,
                'role_code' => strtolower($role)
            ]);
            $permission = ($permission or (!$profile ? false : true));
        }

        return $permission;
    }
    
    public function addProfileAdminByMenu($intMenuId)
    {
        $intGroupId = config('cms.backend.super_admin_group_id');
        $businessRole = Globals::getBusiness('Role');
        $arrRoleCode = $businessRole->getListRole();
        
        if (count($arrRoleCode) > 0) {
            $arrRoleCode = $arrRoleCode->pluck('role_code')->toArray();
            
            foreach ($arrRoleCode as $roleCode) {
                $this->create([
                    'group_id' => $intGroupId,
                    'menu_id' => $intMenuId,
                    'role_code' => $roleCode
                ]);
            }
        }
    }
    
    public function addProfileAdminByRole($roleCode)
    {
        $intGroupId = config('cms.backend.super_admin_group_id');
        $businessMenu = Globals::getBusiness('Menu');
        $arrMenuId = $businessMenu->getListMenu();
        
        if (count($arrMenuId) > 0) {
            $arrMenuId = $arrMenuId->pluck('menu_id')->toArray();
            
            foreach ($arrMenuId as $intMenuId) {
                $this->create([
                    'group_id' => $intGroupId,
                    'menu_id' => $intMenuId,
                    'role_code' => $roleCode
                ]);
            }
        }
    }
}
