<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Relationship\PageRelationship;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Entity\Business\Traits\Behavior\PageBehavior;

class Business_Page extends Model
{
    use CustomSoftDeletes;
    use TrackingLog;
    use BasicBehavior;
    use PageBehavior;
    use PageRelationship;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'page_id';

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
        'page_title',
        'page_code',
        'page_content',
        'page_seo_title',
        'page_seo_keywords',
        'page_seo_description',
        'parent_id',
        'language_id',
        'status',
    ];
}
