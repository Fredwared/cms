<?php
namespace App\Models\Entity\Business\Traits\Relationship;

trait BlockFunctionRelationship
{
    public function templates()
    {
        return $this->belongsToMany('App\Models\Entity\Business\Business_Block_Template', 'block_template_function', 'function_id', 'template_id');
    }
}
