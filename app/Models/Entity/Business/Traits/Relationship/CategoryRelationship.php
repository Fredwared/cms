<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait CategoryRelationship
{
    public function parent()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Category', 'cateparent_id', 'category_id');
    }

    public function childs()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Category', 'cateparent_id', 'category_id')->orderBy('category_order', 'asc');
    }

    public function articles()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Article', 'article_category', 'category_id', 'article_id')->orderBy('article_score', 'desc');
    }

    public function langmaps()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_LangMap', 'source_item_id', 'category_id')->where('item_module', 'category');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Article', 'product_category', 'category_id', 'product_id');
    }
}
