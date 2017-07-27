<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait MenuBehavior
{
    protected $_arrTemp;

    public function scopeIsParent($query, $condition)
    {
        return $query->where('parent_id', '=', $condition);
    }

    public function getListMenu($params = [])
    {
        $this->_arrTemp = [];
        
        $params = array_merge([
            'parent_id' => -1,
            'status' => null,
        ], $params);

        if ($params['parent_id'] >= 0) {
            $query = $this->isParent($params['parent_id'])->orderBy('parent_id', 'ASC')->orderBy('display_order', 'ASC');
        } else {
            $query = $this->orderBy('parent_id', 'ASC')->orderBy('display_order', 'ASC');
        }

        if (!empty($params['status'])) {
            $query->where('status', '=', $params['status']);
        }
        
        $query->with('parent')->with('childs')->with('profiles');

        $arrListMenu = $query->get();
        $this->makeTree($arrListMenu);

        return $this->_arrTemp;
    }

    public function makeTree($arrMenuList, $parentID = 0, $level = 1, $fullParentId = '')
    {
        foreach ($arrMenuList as $menu) {
            if ($menu->parent_id == $parentID) {
                $menu->full_parent_id = $fullParentId . ($fullParentId != '' ? ',' : '') . $parentID;
                $menu->menu_level = $level;

                $this->_arrTemp[$menu->menu_id] = $menu;
                $this->makeTree($arrMenuList, $menu->menu_id, $level + 1, $menu->full_parent_id);
            }
        }
    }

    public function getMenuIdByCode($menuCode)
    {
        $menu = $this->findByAttributes(['menu_code' => $menuCode]);

        if ($menu) {
            return $menu->menu_id;
        }

        return 0;
    }
    
    public function getListMenuByUser()
    {
        $user = auth('backend')->user();
        $arrMenu = [];

        foreach ($user->groups->where('status', 1) as $group) {
            foreach ($group->profiles as $profile) {
                if ($profile->role_code == 'view') {
                    $arrMenu[] = $profile->menu_id;
                }
            }
        }

        return array_unique($arrMenu);
    }
    
    public function getLastOrder($intParentId = 0)
    {
        $result = $this->where('parent_id', '=', $intParentId)->count();

        return $result + 1;
    }
}
