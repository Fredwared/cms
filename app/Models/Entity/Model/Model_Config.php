<?php
namespace App\Models\Entity\Model;

use App\Models\Entity\Business\Business_Config;
use App\Models\Services\Caching;
use App\Models\Services\Globals;

class Model_Config extends Business_Config
{
    public function getConfigByName($params)
    {
        $params = array_merge([
            'pre_cache' => false,
            'type' => 'create'
        ], $params);

        $keyCache = Globals::makeCacheKey(config('cms.cache.key.config'), [$params['field_name']]);

        if ($params['pre_cache'] && $params['type'] == 'delete') {
            Caching::getInstance()->deleteCache($keyCache);

            return '';
        }

        $value = Caching::getInstance()->getCache($keyCache);

        if ($params['pre_cache'] || empty($value)) {
            $arrData = $this->where('field_name', $params['field_name'])->first();

            if ($arrData) {
                $value = $arrData->field_value;
                Caching::getInstance()->writeCache($keyCache, $value);
            }
        }

        return $value;
    }
}
