<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait RoleBehavior
{
    public function scopeStatus($query, $condition)
    {
        return $query->where('status', '=', $condition);
    }

    public function getListRole($status = null)
    {
        if (!empty($status)) {
            $query = $this->status($status)->orderBy('role_priority', 'ASC');
        } else {
            $query = $this->orderBy('role_priority', 'ASC');
        }
        
        $query->with('profiles');

        return $query->get();
    }

    public function getRoleByAction($strAction)
    {
        $query = $this->status(config('cms.backend.status.active'))->whereRaw("FIND_IN_SET('" . $strAction . "',`action_applied`)")->with('profiles');

        return $query->first();
    }
}
