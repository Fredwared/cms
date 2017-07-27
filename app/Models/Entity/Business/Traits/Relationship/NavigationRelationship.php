<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait NavigationRelationship
{
    public function parent()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Navigation', 'parent_id', 'navigation_id');
    }

    public function childs()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Navigation', 'parent_id', 'navigation_id');
    }

    public function page()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Page', 'navigation_type_id', 'page_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Category', 'navigation_type_id', 'category_id');
    }
}
