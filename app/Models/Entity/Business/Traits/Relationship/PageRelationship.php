<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait PageRelationship
{
    public function parent()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Page', 'parent_id', 'page_id');
    }

    public function childs()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Page', 'parent_id', 'page_id');
    }
}
