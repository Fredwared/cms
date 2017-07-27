<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait UserRelationship
{
    public function groups()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Group', 'group_user', 'user_id', 'group_id');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Log', 'user_id', 'id');
    }

    public function articles()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Article', 'user_id', 'id');
    }
}
