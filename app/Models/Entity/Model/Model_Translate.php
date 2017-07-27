<?php
namespace App\Models\Entity\Model;

use App\Models\Entity\Business\Business_Translate;
use App\Models\Services\Caching;

class Model_Translate extends Business_Translate
{
    public function getTranslate($params)
    {
        $params = array_merge([
            'pre_cache' => false,
            'type' => 'create'
        ], $params);

        $keyCache = Globals::makeCacheKey(config('cms.cache.key.translate'), [$params['translate_code'], $params['translate_mode']]);

        if ($params['pre_cache'] && $params['type'] == 'delete') {
            Caching::getInstance()->deleteCache($keyCache);

            return '';
        }

        $value = Caching::getInstance()->getCache($keyCache);

        if ($params['pre_cache'] || empty($value)) {
            $arrData = $this->where([
                'translate_code' => $params['translate_code'],
                'translate_mode' => $params['translate_mode']
            ])->first();

            if ($arrData) {
                $value = $arrData->translate_content;
                Caching::getInstance()->writeCache($keyCache, $value);
            }
        }

        return $value;
    }
}
