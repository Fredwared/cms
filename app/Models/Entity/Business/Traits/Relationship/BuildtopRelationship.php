<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait BuildtopRelationship
{
    public function article()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Article', 'type_id', 'article_id');
    }
    
    public function product()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Product', 'type_id', 'product_id');
    }
}
