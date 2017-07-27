<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Relationship\RoleRelationship;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Entity\Business\Traits\Behavior\RoleBehavior;
use App\Models\Traits\TrackingLog;

class Business_Role extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use RoleBehavior;
    use RoleRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'role_code';
    
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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
        'role_code',
        'role_name',
        'role_priority',
        'action_applied',
        'status',
    ];
}
