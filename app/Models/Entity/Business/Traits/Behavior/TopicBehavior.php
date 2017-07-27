<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait TopicBehavior
{
    public function getListTopic($params)
    {
        $params = array_merge([
            'category_id' =>  null,
            'language_id' => config('app.locale'),
            'status' => null,
            'title' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        $query = $this->orderBy('updated_at', 'DESC');

        if (!empty($params['category_id'])) {
            $query->where('category_id', '=', $params['category_id']);
        }

        if (!empty($params['status'])) {
            $query->where('status', '=', $params['status']);
        }

        if (!empty($params['title'])) {
            $query->where('topic_title', 'like', '%' . $params['title'] . '%');
        }

        if (!empty($params['language_id'])) {
            $query->where('language_id', '=', $params['language_id']);
        }

        $query->with('articles')->with('category');

        return $this->doPaginate($query, $params['item'], $params['page']);
    }
}
