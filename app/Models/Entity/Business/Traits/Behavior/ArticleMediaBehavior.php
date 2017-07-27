<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait ArticleMediaBehavior
{
    public function addByArticle($intArticleId, $arrData = [])
    {
        $this->forceDeleteByAttributes([
            'article_id' => $intArticleId
        ]);

        if (!empty($arrData)) {
            foreach ($arrData as $data) {
                $this->create([
                    'article_id' => $intArticleId,
                    'media_id' => $data['media_id'],
                    'type' => $data['type']
                ]);
            }
        }
    }
}
