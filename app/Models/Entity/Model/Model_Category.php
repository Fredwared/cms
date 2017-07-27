<?php
namespace App\Models\Entity\Model;

use App\Models\Entity\Business\Business_Category;
use App\Models\Services\Globals;
use App\Models\Services\Caching;

class Model_Category extends Business_Category
{
    public function getList($params)
    {
        $keyCache = Globals::makeCacheKey(config('cms.cache.key.category.list'), [$params['language_id'], $params['category_type']]);
        $arrData = Caching::getInstance()->getCache($keyCache);

        if (empty($arrData)) {
            $arrData = $this->getListCategory([
                'language_id' => $params['language_id'],
                'category_type' =>  $params['category_type'],
                'status' => config('cms.backend.status.active')
            ]);

            if (!empty($arrData)) {
                Caching::getInstance()->writeCache($keyCache, $arrData);
            }
        }

        return $arrData;
    }

    public function getDetail($intCategoryId)
    {

    }
}
