<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait ArticleRelationship
{
    public function medias()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Media', 'article_media', 'article_id', 'media_id');
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
        return $this->belongsToMany('App\Models\Entity\Business\Business_Category', 'article_category', 'article_id', 'category_id');
    }

    public function langmaps()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_LangMap', 'source_item_id', 'article_id')->where('item_module', 'article');
    }
    
    public function references()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Article', 'article_reference', 'article_id', 'reference_id');
    }

    public function topics()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Topic', 'article_topic', 'article_id', 'topic_id');
    }
}
