<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait SlideBehavior
{
    public function getListSlide($params)
    {
        $params = array_merge([
            'title' => null,
            'language_id' => config('app.locale'),
            'type' => config('cms.backend.slide.default'),
            'status' => null,
            'item' => 0,
            'page' => 1,
        ], $params);

        $query = $this->orderBy('slide_order', 'ASC');

        if (!empty($params['title'])) {
            $query->where('slide_title', 'like', '%' . $params['title'] . '%');
        }

        if (!empty($params['language_id'])) {
            $query->where('language_id', '=', $params['language_id']);
        }

        if (!empty($params['type'])) {
            $query->where('slide_type', '=', $params['type']);
        }

        if (!empty($params['status'])) {
            $query->where('status', '=', $params['status']);
        }

        return $this->doPaginate($query, $params['item'], $params['page']);
    }

    public function getLastOrder($languageId, $type = 'home')
    {
        $result = $this->where([
            'language_id' => $languageId,
            'slide_type' => $type
        ])
        ->count();

        return $result + 1;
    }
}
