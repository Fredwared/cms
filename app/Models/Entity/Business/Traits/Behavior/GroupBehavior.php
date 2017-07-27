<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait GroupBehavior
{
    public function getListGroup($params = [])
    {
        $params = array_merge([
            'status' => null,
            'item' => 0,
            'page' => 1
        ], $params);
    
        $query = $this->where('group_id', '<>', config('cms.backend.super_admin_group_id'))
            ->orderBy('updated_at', 'DESC');
        
        if (!empty($params['status'])) {
            $query->where('status', '=', $params['status']);
        }
        
        $query->with('parent')->with('childs')->with('users')->with('profiles');

        return $this->doPaginate($query, $params['item'], $params['page']);
    }

    public function searchGroup($params = [])
    {
        $params = array_merge([
            'keyword' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        $query = $this->where('status', '=', 1)
            ->where('group_id', '<>', config('cms.backend.super_admin_group_id'))
            ->orderBy('group_name', 'ASC');
        
        if (!empty($params['keyword'])) {
            $query->where('group_name', 'like', '%' . $params['keyword'] . '%');
        }
        
        $query->with('parent')->with('childs')->with('users');

        return $this->doPaginate($query, $params['item'], $params['page']);
    }
}
