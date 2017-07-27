<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait ConfigBehavior
{
    public function getListConfig($params = [])
    {
        $params = array_merge([
            'field_name' => null,
            'field_value' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        $query = $this->orderBy('updated_at', 'DESC');

        if (!empty($params['field_name'])) {
            $query->where('field_name', 'like', '%' . $params['field_name'] . '%');
        }

        if (!empty($params['field_value'])) {
            $query->where('field_value', 'like', '%' . $params['field_value'] . '%');
        }

        return $this->doPaginate($query, $params['item'], $params['page']);
    }
}
