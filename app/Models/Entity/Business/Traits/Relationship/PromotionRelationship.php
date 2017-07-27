<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait PromotionRelationship
{
    public function langmaps()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_LangMap', 'source_item_id', 'promotion_id')->where('item_module', 'promotion');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Topic', 'product_promotion', 'promotion_id', 'product_id');
    }
}
