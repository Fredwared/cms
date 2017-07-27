<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait MenuRelationship
{
    public function parent()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Menu', 'parent_id', 'menu_id');
    }
    
    public function childs()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Menu', 'parent_id', 'menu_id')->orderBy('display_order', 'asc');
    }
    
    public function profiles()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Profile', 'menu_id', 'menu_id');
    }
}
