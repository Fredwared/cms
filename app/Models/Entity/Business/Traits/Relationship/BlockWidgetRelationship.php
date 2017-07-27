<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait BlockWidgetRelationship
{
    public function template()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Block_Template', 'template_id', 'template_id');
    }

    public function func()
    {
        return $this->belongsTo('App\Models\Entity\Business\Business_Block_Function', 'function_id', 'function_id');
    }
}
