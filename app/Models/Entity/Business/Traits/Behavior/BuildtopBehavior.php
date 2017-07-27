<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait BuildtopBehavior
{
    public function getListBuildtop($params)
    {
        $params = array_merge([
            'type' =>  config('cms.backend.buildtop.type.article'),
            'category_id' => 0,
            'language_id' => config('app.locale')
        ], $params);
        
        $query = $this->orderBy('order', 'asc');

        if (!empty($params['type'])) {
            $query->where('type', '=', $params['type']);
        }

        if (!empty($params['category_id'])) {
            $query->where('category_id', '=', $params['category_id']);
        }

        if (!empty($params['language_id'])) {
            $query->where('language_id', '=', $params['language_id']);
        }

        $query->with($params['type']);

        return $query->get();
    }
}
