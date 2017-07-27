<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait DistrictBehavior
{
    public function getListDistrict($intCityId)
    {
        return $this->orderBy('district_order', 'ASC')->where('city_id', '=', $intCityId)->get();
    }

    public function getLastOrder($intCityId)
    {
        $result = $this->where([
            'city_id' => $intCityId
        ])->count();

        return $result + 1;
    }
}
