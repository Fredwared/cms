<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Behavior\DistrictBehavior;
use App\Models\Entity\Business\Traits\Relationship\DistrictRelationship;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;

class Business_District extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use DistrictBehavior;
    use DistrictRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'district';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'district_id';
    
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
        'district_id',
        'district_name',
        'district_location',
        'district_order',
        'city_id',
        'status',
    ];
}
