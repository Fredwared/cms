<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait WardRelationship
{
    public function district()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_District', 'district_id', 'district_id');
    }
}
