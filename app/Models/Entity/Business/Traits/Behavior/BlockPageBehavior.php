<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait BlockPageBehavior
{
    public function getListPage($language_id)
    {
        $query = $this->orderBy('page_code', 'asc')->where('language_id', '=', $language_id);

        return $query->get();
    }
}
