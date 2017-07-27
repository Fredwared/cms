<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait BlockTemplateRelationship
{
    public function functions()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Block_Function', 'block_template_function', 'template_id', 'function_id');
    }
}
