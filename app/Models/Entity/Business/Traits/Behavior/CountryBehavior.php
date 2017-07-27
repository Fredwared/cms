<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait CountryBehavior
{
    public function getListCountry()
    {
        return $this->orderBy('country_order', 'ASC')->get();
    }

    public function getLastOrder()
    {
        $result = $this->count();

        return $result + 1;
    }
}
