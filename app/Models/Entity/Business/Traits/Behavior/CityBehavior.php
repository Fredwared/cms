<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait CityBehavior
{
    public function getListCity($intCountryId)
    {
        return $this->orderBy('city_order', 'ASC')->where('country_id', '=', $intCountryId)->get();
    }

    public function getLastOrder($intCountryId)
    {
        $result = $this->where([
            'country_id' => $intCountryId
        ])->count();

        return $result + 1;
    }
}
