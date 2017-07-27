<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait UserBehavior
{
    public function getListUser($params = [])
    {
        $params = array_merge([
            'status' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        $query = $this->where('id', '<>', config('cms.backend.super_admin_id'))
            ->orderBy('updated_at', 'DESC');
        
        if (!empty($params['status'])) {
            $query->where('status', '=', $params['status']);
        }
        
        $query->with('groups')->with('logs')->with('articles');

        return $this->doPaginate($query, $params['item'], $params['page']);
    }
    
    public function searchUser($params = [])
    {
        $params = array_merge([
            'keyword' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        $query = $this->where('status', '=', 1)
            ->where('id', '<>', config('cms.backend.super_admin_id'))
            ->orderBy('fullname', 'ASC');
        
        if (!empty($params['keyword'])) {
            $query->where(function ($query) use ($params) {
                $query->where('fullname', 'like', '%' . $params['keyword'] . '%')
                    ->orWhere('email', 'like', '%' . $params['keyword'] . '%');
            });
        }
        
        $query->with('groups')->with('logs')->with('articles');

        return $this->doPaginate($query, $params['item'], $params['page']);
    }
}
