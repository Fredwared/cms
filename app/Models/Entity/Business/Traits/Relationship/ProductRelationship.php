<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait ProductRelationship
{
    public function medias()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Media', 'product_media', 'product_id', 'media_id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_User', 'user_id', 'id');
    }
    
    public function category()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Category', 'category_id', 'category_id');
    }
    
    public function categories()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Category', 'product_category', 'product_id', 'category_id');
    }

    public function langmaps()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_LangMap', 'source_item_id', 'product_id')->where('item_module', 'product');
    }

    public function promotions()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Topic', 'product_promotion', 'product_id', 'promotion_id');
    }
}
