<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Behavior\WardBehavior;
use App\Models\Entity\Business\Traits\Relationship\WardRelationship;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;

class Business_Ward extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use WardBehavior;
    use WardRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ward';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ward_id';
    
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
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ward_id',
        'ward_name',
        'ward_location',
        'ward_order',
        'district_id',
        'status',
    ];
}
