<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Behavior\MediaBehavior;
use App\Models\Entity\Business\Traits\Relationship\MediaRelationship;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use Illuminate\Database\Eloquent\Model;

class Business_Media extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use MediaBehavior;
    use MediaRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'media_id';
    
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
        'media_title',
        'media_filename',
        'media_info',
        'media_type',
        'media_label',
        'media_source',
    ];
}
