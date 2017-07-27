<?php
namespace App\Models\Entity\Business\Traits\Behavior;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

trait PageBehavior
{
    protected $_arrTemp;

    public function scopeIsParent($query, $condition)
    {
        return $query->where('parent_id', '=', $condition);
    }

    public function getListPage($params)
    {
        $this->_arrTemp = [];

        $params = array_merge([
            'parent_id' => -1,
            'language_id' => config('app.locale'),
            'status' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        if ($params['parent_id'] >= 0) {
            $query = $this->isParent($params['parent_id'])->orderBy('parent_id', 'ASC');
        } else {
            $query = $this->orderBy('parent_id', 'ASC');
        }

        if (!empty($params['status'])) {
            $query->where('status', '=', $params['status']);
        }

        if (!empty($params['language_id'])) {
            $query->where('language_id', '=', $params['language_id']);
        }

        $query->with('parent')->with('childs');

        $arrListPage = $query->get();
        $this->makeTree($arrListPage);

        if ($params['item'] > 0) {
            $arrData = collect($this->_arrTemp);

            $total = $arrData->count();
            $result = $arrData->slice(($params['page'] - 1) * $params['item'], $params['item']);

            return new LengthAwarePaginator($result, $total, $params['item'], $params['page'], [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]);
        }

        return $this->_arrTemp;
    }

    public function makeTree($arrListPage, $parentID = 0, $level = 1)
    {
        foreach ($arrListPage as $page) {
            if ($page->parent_id == $parentID) {
                $page->page_level = $level;

                $this->_arrTemp[$page->page_id] = $page;
                $this->makeTree($arrListPage, $page->page_id, $level + 1);
            }
        }
    }
}
