<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait BlockWidgetBehavior
{
    public function getListWidget($params)
    {
        $params = array_merge([
            'page_code' =>  null,
            'widget_area' => null,
            'widget_type' => null
        ], $params);

        $query = $this->orderBy('widget_order', 'ASC');

        if (!empty($params['page_code'])) {
            $query->where('page_code', '=', $params['page_code']);
        }

        if (!empty($params['widget_area'])) {
            $query->where('widget_area', '=', $params['widget_area']);
        }

        if (!empty($params['widget_type'])) {
            $query->where('widget_type', '=', $params['widget_type']);
        }

        $query->with('template')->with('func');

        return $query->get();
    }
}
