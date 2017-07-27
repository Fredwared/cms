<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Relationship\MenuRelationship;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Entity\Business\Traits\Behavior\MenuBehavior;
use App\Models\Traits\TrackingLog;

class Business_Menu extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use MenuBehavior;
    use MenuRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menu';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'menu_id';
    
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
        'menu_name',
        'menu_code',
        'route_name',
        'menu_icon',
        'parent_id',
        'status',
        'display_order',
    ];
}
