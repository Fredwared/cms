<?php
namespace App\Models\Entity\Business\Traits\Behavior;

trait ArticleTopicBehavior
{
    public function addTopic($intArticleId, $arrTopicId = [])
    {
        $this->forceDeleteByAttributes(['article_id' => $intArticleId]);

        if (!empty($arrTopicId)) {
            foreach ($arrTopicId as $intTopicId) {
                if (!$intTopicId) {
                    continue;
                }

                $this->create([
                    'article_id' => $intArticleId,
                    'topic_id' => $intTopicId
                ]);
            }
        }
    }

    public function addArticle($intTopicId, $arrArticleId = [])
    {
        $this->forceDeleteByAttributes(['topic_id' => $intTopicId]);

        if (!empty($arrArticleId)) {
            foreach ($arrArticleId as $intArticleId) {
                if (!$intArticleId) {
                    continue;
                }

                $this->create([
                    'article_id' => $intArticleId,
                    'topic_id' => $intTopicId
                ]);
            }
        }
    }
}
