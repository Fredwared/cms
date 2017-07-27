<?php
namespace App\Models\Entity\Business\Traits\Behavior;

use DB;

trait LangMapBehavior
{
    public function getSourceByItem($params)
    {
        $result = $this->where('item_module', '=', $params['item_module'])
            ->where('item_id', '=', $params['item_id'])
            ->where('language_id', '=', $params['language_id'])
            ->first();
        
        return $result ? $result->source_item_id : 0;
    }
    
    public function getItemBySource($params)
    {
        $result = $this->where('item_module', '=', $params['item_module'])
            ->where('language_id', '=', $params['language_id'])
            ->where('source_item_id', '=', $params['source_item_id'])
            ->where('source_language_id', '=', $params['source_language_id'])
            ->first();
    
        return $result ? $result->item_id : 0;
    }
    
    public function getTranslations($intItemId, $strItemModule)
    {
        $arrData = DB::select('call sp_be_getTranslations(?, ?)', array($intItemId, $strItemModule));
        
        return $arrData;
    }
}
