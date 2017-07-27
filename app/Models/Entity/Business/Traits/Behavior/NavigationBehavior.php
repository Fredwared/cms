<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait NavigationBehavior
{
    protected $_arrTemp;

    public function scopeIsParent($query, $condition)
    {
        return $query->where('parent_id', '=', $condition);
    }

    public function getListNavigation($params)
    {
        $this->_arrTemp = [];

        $params = array_merge([
            'parent_id' => -1,
            'language_id' => config('app.locale')
        ], $params);

        if ($params['parent_id'] >= 0) {
            $query = $this->isParent($params['parent_id'])->orderBy('parent_id', 'ASC')->orderBy('navigation_order', 'ASC');
        } else {
            $query = $this->orderBy('parent_id', 'ASC')->orderBy('navigation_order', 'ASC');
        }

        if (!empty($params['language_id'])) {
            $query->where('language_id', '=', $params['language_id']);
        }

        $query->with('parent')->with('childs')->with('page')->with('category');

        $arrListNavigation = $query->get();
        $this->makeTree($arrListNavigation);

        return $this->_arrTemp;
    }

    public function makeTree($arrListNavigation, $parentID = 0, $level = 1)
    {
        foreach ($arrListNavigation as $navigation) {
            if ($navigation->parent_id == $parentID) {
                $navigation->navigation_level = $level;

                $this->_arrTemp[$navigation->navigation_id] = $navigation;
                $this->makeTree($arrListNavigation, $navigation->navigation_id, $level + 1);
            }
        }
    }

    public function getLastOrder($intParentId = 0, $languageId)
    {
        $result = $this->where([
            'parent_id' => $intParentId,
            'language_id' => $languageId
        ])->count();

        return $result + 1;
    }
}
