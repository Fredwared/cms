<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait TopicRelationship
{
    public function articles()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Article', 'article_topic', 'topic_id', 'article_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Category', 'category_id', 'category_id');
    }
}
