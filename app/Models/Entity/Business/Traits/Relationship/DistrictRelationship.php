<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait DistrictRelationship
{
    public function city()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_City', 'city_id', 'city_id');
    }

    public function wards()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_Ward', 'district_id', 'district_id');
    }
}
