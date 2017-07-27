<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Behavior\CityBehavior;
use App\Models\Entity\Business\Traits\Relationship\CityRelationship;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;

class Business_City extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use CityBehavior;
    use CityRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'city';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'city_id';
    
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
        'city_id',
        'city_name',
        'city_zipcode',
        'city_location',
        'city_order',
        'country_id',
        'status',
    ];
}
