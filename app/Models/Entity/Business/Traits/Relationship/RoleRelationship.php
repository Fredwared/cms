<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait RoleRelationship
{
    public function profiles()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Profile', 'role_code', 'role_code');
    }
}
