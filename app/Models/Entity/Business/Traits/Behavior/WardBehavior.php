<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait WardBehavior
{
    public function getListWard($intDistrictId)
    {
        return $this->orderBy('ward_order', 'ASC')->where('district_id', '=', $intDistrictId)->get();
    }

    public function getLastOrder($intDistrictId)
    {
        $result = $this->where([
            'district_id' => $intDistrictId
        ])->count();

        return $result + 1;
    }
}
