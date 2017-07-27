<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait ProductMediaBehavior
{
    public function addByProduct($intProductId, $arrData = [])
    {
        $this->forceDeleteByAttributes([
            'product_id' => $intProductId
        ]);

        if (!empty($arrData)) {
            foreach ($arrData as $data) {
                $this->create([
                    'product_id' => $intProductId,
                    'media_id' => $data['media_id'],
                    'type' => $data['type']
                ]);
            }
        }
    }
}
