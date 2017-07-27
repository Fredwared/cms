<?php
namespace App\Models\Entity\Business;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use App\Models\Entity\Business\Traits\Behavior\ArticleBehavior;
use App\Models\Entity\Business\Traits\Relationship\ArticleRelationship;

class Business_Article extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use ArticleBehavior;
    use ArticleRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'article_id';
    
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'article_title',
        'article_code',
        'article_description',
        'article_content',
        'article_score',
        'article_priority',
        'article_author',
        'thumbnail_url',
        'thumbnail_url2',
        'language_id',
        'category_id',
        'category_liston',
        'article_comment',
        'article_privacy',
        'article_tags',
        'share_url',
        'article_seo_title',
        'article_seo_keywords',
        'article_seo_description',
        'user_id',
        'published_at',
        'status',
    ];
}
