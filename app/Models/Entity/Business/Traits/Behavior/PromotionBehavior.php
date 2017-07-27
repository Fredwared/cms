<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait PromotionBehavior
{
    public function getListPromotion($params)
    {
        $params = array_merge([
            'language_id' => config('app.locale'),
            'status' => null,
            'item' => 0,
            'page' => 1
        ], $params);

        $query = $this->orderBy('promotion_id', 'DESC');
        $query->with('langmaps')->with('products');

        return $this->doPaginate($query, $params['item'], $params['page']);
    }
}
