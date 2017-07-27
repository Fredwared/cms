<?php
namespace App\Models\Entity\Business;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use App\Models\Entity\Business\Traits\Behavior\SlideBehavior;
use App\Models\Entity\Business\Traits\Relationship\SlideRelationship;

class Business_Slide extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use SlideBehavior;
    use SlideRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'slide';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'slide_id';
    
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
        'slide_title',
        'slide_description',
        'slide_image',
        'slide_link',
        'slide_target',
        'slide_type',
        'slide_order',
        'language_id',
        'status',
    ];
}
