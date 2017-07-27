<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait GroupRelationship
{
    public function parent()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Group', 'parent_id', 'group_id');
    }

    public function childs()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Group', 'parent_id', 'group_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_User', 'group_user', 'group_id', 'user_id');
    }

    public function profiles()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Profile', 'group_id', 'group_id');
    }
}
