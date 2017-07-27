<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait TagBehavior
{
    public function searchTag($params = [])
    {
        $params = array_merge([
            'keyword' => null,
            'language_id' => config('app.locale'),
            'item' => 0,
            'page' => 1,
        ], $params);
    
        $query = $this->orderBy('updated_at', 'DESC');
    
        if (!empty($params['keyword'])) {
            $query->where('tag_name', 'like', '%' . $params['keyword'] . '%');
        }
        
        if (!empty($params['language_id'])) {
            $query->where('language_id', '=', $params['language_id']);
        }

        return $this->doPaginate($query, $params['item'], $params['page']);
    }
}
