<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Relationship\NavigationRelationship;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Entity\Business\Traits\Behavior\NavigationBehavior;

class Business_Navigation extends Model
{
    use CustomSoftDeletes;
    use TrackingLog;
    use BasicBehavior;
    use NavigationBehavior;
    use NavigationRelationship;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigation';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'navigation_id';

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
        'navigation_title',
        'navigation_url',
        'navigation_order',
        'navigation_type',
        'navigation_type_id',
        'language_id',
        'parent_id',
    ];
}
