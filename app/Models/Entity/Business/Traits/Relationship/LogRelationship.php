<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait LogRelationship
{
    public function user()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_User', 'user_id', 'id');
    }
}
