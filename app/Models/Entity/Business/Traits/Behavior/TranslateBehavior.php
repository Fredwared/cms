<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait TranslateBehavior
{
    public function getListTranslate($params = [])
    {
        $params = array_merge([
            'translate_code' => null,
            'translate_mode' => null,
            'translate_content' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        $query = $this->orderBy('updated_at', 'DESC');

        if (!empty($params['translate_code'])) {
            $query->where('translate_code', 'like', '%' . $params['translate_code'] . '%');
        }

        if (!empty($params['translate_mode'])) {
            $query->where('translate_mode', '=', $params['translate_mode']);
        }

        if (!empty($params['translate_content'])) {
            $query->where('translate_content', 'like', '%' . $params['translate_content'] . '%');
        }

        return $this->doPaginate($query, $params['item'], $params['page']);
    }
}
