<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait BlockFunctionBehavior
{
    public function getListFunction()
    {
        $query = $this->orderBy('function_name', 'asc')->with('templates');

        return $query->get();
    }
}
