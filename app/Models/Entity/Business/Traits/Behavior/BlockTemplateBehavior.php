<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait BlockTemplateBehavior
{
    public function getListTemplate($params = [])
    {
        $params = array_merge([
            'template_area' => null,
            'template_type' => null
        ], $params);

        $query = $this->orderBy('template_name', 'asc');

        if (!empty($params['template_area'])) {
            $query->where('template_area', '=', $params['template_area']);
        }

        if (!empty($params['template_type'])) {
            $query->where('template_type', '=', $params['template_type']);
        }

        $query->with('functions');

        return $query->get();
    }
}
