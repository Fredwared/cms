<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait ProfileRelationship
{
    public function group()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Group', 'group_id', 'group_id');
    }

    public function menu()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Menu', 'menu_id', 'menu_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Role', 'role_code', 'role_code');
    }
}
