<?php
namespace App\Models\Entity\Business\Traits\Behavior;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

trait CategoryBehavior
{
    protected $_arrTemp;

    public function scopeIsParent($query, $condition)
    {
        return $query->where('cateparent_id', '=', $condition);
    }

    public function getListCategory($params)
    {
        $this->_arrTemp = [];
        
        $params = array_merge([
            'cateparent_id' => -1,
            'language_id' => config('app.locale'),
            'category_type' =>  config('cms.backend.category.type.article'),
            'status' => null,
            'item' => 0,
            'page' => 1
        ], $params);
        $params['max_level'] = config('cms.backend.category.max_level.' . config('cms.backend.category.name.' . $params['category_type']));
        
        if ($params['cateparent_id'] >= 0) {
            $query = $this->isParent($params['cateparent_id'])->orderBy('cateparent_id', 'ASC')->orderBy('category_order', 'ASC');
        } else {
            $query = $this->orderBy('cateparent_id', 'ASC')->orderBy('category_order', 'ASC');
        }

        if (!empty($params['status'])) {
            $query->where('status', '=', $params['status']);
        }

        if (!empty($params['language_id'])) {
            $query->where('language_id', '=', $params['language_id']);
        }

        if (!empty($params['category_type'])) {
            $query->where('category_type', '=', $params['category_type']);
        }
        
        $query->with('parent')->with('childs')->with('articles');

        $arrListCategory = $query->get();
        $this->makeTree($arrListCategory, $params['max_level']);
        
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

    public function makeTree($arrListCategory, $maxLevel, $parentID = 0, $level = 1, $fullParentId = '')
    {
        if ($level <= $maxLevel) {
            foreach ($arrListCategory as $category) {
                if ($category->cateparent_id == $parentID) {
                    $category->cateparent_full = $fullParentId . ($fullParentId != '' ? ',' : '') . $parentID;
                    $category->category_level = $level;

                    $this->_arrTemp[$category->category_id] = $category;
                    $this->makeTree($arrListCategory, $maxLevel, $category->category_id, $level + 1, $category->cateparent_full);
                }
            }
        }
    }
    
    public function getLastOrder($intParentId = 0, $languageId)
    {
        $result = $this->where([
            'cateparent_id' => $intParentId,
            'language_id' => $languageId
        ])->count();
        
        return $result + 1;
    }

    public function getFullChildId($intParentId = 0)
    {
        $arrData = $this->where('cateparent_id', '=', $intParentId)->get(['category_id'])->toArray();

        if (!empty($arrData)) {
            foreach ($arrData as $id) {
                $arrData = array_merge($arrData, $this->getFullChildId($id));
            }
        }

        return $arrData;
    }

    public function getFullParentId($intCategoryId)
    {
        $arrData = [];
        $categoryinfo = $this->find($intCategoryId);

        if ($categoryinfo && $categoryinfo->cateparent_id != 0) {
            $arrData[] = $categoryinfo->cateparent_id;
            $arrData = array_merge($arrData, $this->getFullParentId($categoryinfo->cateparent_id));
        }

        return $arrData;
    }
}
