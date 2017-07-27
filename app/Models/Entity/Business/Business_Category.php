<?php
namespace App\Models\Entity\Business;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use App\Models\Entity\Business\Traits\Behavior\CategoryBehavior;
use App\Models\Entity\Business\Traits\Relationship\CategoryRelationship;

class Business_Category extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use CategoryBehavior;
    use CategoryRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'category_id';
    
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
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_title',
        'category_code',
        'category_description',
        'category_order',
        'category_seo_title',
        'category_seo_keywords',
        'category_seo_description',
        'category_showfe',
        'category_icon',
        'cateparent_id',
        'language_id',
        'status',
        'category_type',
    ];
}
