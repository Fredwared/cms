<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait BlockTemplateFunctionBehavior
{
    public function addByTemplate($intTemplateId, $arrFunctionId = [])
    {
        $this->forceDeleteByAttributes([
            'template_id' => $intTemplateId
        ]);

        if (!empty($arrFunctionId)) {
            foreach ($arrFunctionId as $function_id) {
                $this->create([
                    'template_id' => $intTemplateId,
                    'function_id' => $function_id
                ]);
            }
        }
    }
}
