<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait ArticleCategoryBehavior
{
    public function addByArticle($intArticleId, $arrCategoryId = [])
    {
        $this->forceDeleteByAttributes(['article_id' => $intArticleId]);
    
        foreach ($arrCategoryId as $intCategoryId) {
            if (!$intCategoryId) {
                continue;
            }

            $this->create([
                'article_id' => $intArticleId,
                'category_id' => $intCategoryId
            ]);
        }
    }
}
