<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait CountryRelationship
{
    public function cities()
    {
        return $this->hasMany('App\Models\Entity\Business\Business_City', 'country_id', 'country_id');
    }

    public function districts()
    {
        return $this->hasManyThrough('App\Models\Entity\Business\Business_District', 'App\Models\Entity\Business\Business_City', 'country_id', 'city_id', 'district_id');
    }
}
