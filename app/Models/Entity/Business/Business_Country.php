<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Behavior\CountryBehavior;
use App\Models\Entity\Business\Traits\Relationship\CountryRelationship;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;

class Business_Country extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use CountryBehavior;
    use CountryRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'country';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'country_id';
    
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
        'country_id',
        'country_code',
        'country_name',
        'country_order',
        'status',
    ];
}
