<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait ProductCategoryBehavior
{
    public function addByProduct($intProductId, $arrCategoryId = [])
    {
        $this->forceDeleteByAttributes(['product_id' => $intProductId]);

        if (!empty($arrCategoryId)) {
            foreach ($arrCategoryId as $intCategoryId) {
                if (!$intCategoryId) {
                    continue;
                }

                $this->create([
                    'product_id' => $intProductId,
                    'category_id' => $intCategoryId
                ]);
            }
        }
    }
}
