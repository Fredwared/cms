<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait ArticleReferenceBehavior
{
    public function addReference($intArticleId, $arrReferenceId = [])
    {
        $this->forceDeleteByAttributes(['article_id' => $intArticleId]);

        if (!empty($arrReferenceId)) {
            foreach ($arrReferenceId as $intReferenceId) {
                if (!$intReferenceId) {
                    continue;
                }

                $this->create([
                    'article_id' => $intArticleId,
                    'reference_id' => $intReferenceId
                ]);
            }
        }
    }
}
