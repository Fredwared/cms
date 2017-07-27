<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait LangMapRelationship
{
    public function category()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Category', 'source_item_id', 'category_id')->where('item_module', 'category');
    }

    public function article()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Article', 'source_item_id', 'article_id')->where('item_module', 'article');
    }
}
