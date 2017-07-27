<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait CityRelationship
{
    public function country()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Country', 'country_id', 'country_id');
    }

    public function districts()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_District', 'city_id', 'city_id');
    }

    public function wards()
    {
        return $this->hasManyThrough('App\Models\Entity\Business\Business_Ward', 'App\Models\Entity\Business\Business_District', 'city_id', 'district_id', 'ward_id');
    }
}
