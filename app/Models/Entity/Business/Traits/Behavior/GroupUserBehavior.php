<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait GroupUserBehavior
{
    public function addGroupUserByGroup($intGroupId, $arrUserId = [])
    {
        $this->forceDeleteByAttributes([
            'group_id' => $intGroupId
        ]);

        if (!empty($arrUserId)) {
            foreach ($arrUserId as $intUserId) {
                $this->create([
                    'group_id' => $intGroupId,
                    'user_id' => $intUserId
                ]);
            }
        }
    }

    public function addGroupUserByUser($intUserId, $arrGroupId = [])
    {
        $this->forceDeleteByAttributes([
            'user_id' => $intUserId
        ]);

        if (!empty($arrGroupId)) {
            foreach ($arrGroupId as $intGroupId) {
                $this->create([
                    'group_id' => $intGroupId,
                    'user_id' => $intUserId
                ]);
            }
        }
    }
}
