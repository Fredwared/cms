<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait MediaLabelBehavior
{
    public function searchMediaLabel($params = [])
    {
        $params = array_merge([
            'media_type' => 1,
            'keyword' => null,
            'item' => 0,
            'page' => 1,
        ], $params);
        $params['item'] = ($params['item'] == 0) ? 1000000000 : $params['item'];

        $query = $this->orderBy('updated_at', 'DESC')
            ->where('media_type', '=', $params['media_type']);
        
        if (!empty($params['keyword'])) {
            $query->where('label_name', 'like', '%' . $params['keyword'] . '%');
        }

        return $query->paginate($params['item'], ['*'], 'page', $params['page']);
    }
}
