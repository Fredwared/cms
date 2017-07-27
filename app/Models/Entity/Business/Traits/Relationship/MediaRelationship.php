<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait MediaRelationship
{
    public function articles()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Article', 'article_media', 'media_id', 'article_id');
    }
    public function products()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Product', 'product_media', 'media_id', 'product_id');
    }
}
