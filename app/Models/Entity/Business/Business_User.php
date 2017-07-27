<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Relationship\UserRelationship;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Entity\Business\Traits\Behavior\UserBehavior;
use App\Models\Entity\Business\Traits\Attribute\UserAttribute;
use App\Models\Traits\TrackingLog;

class Business_User extends Authenticatable
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use UserBehavior;
    use UserAttribute;
    use UserRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
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
        'password_updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'fullname',
        'gender',
        'address',
        'phone',
        'avatar',
        'landing_page',
        'default_language',
        'days_password_expired',
        'change_pass_first_login',
        'days_expired_password',
        'password_updated_at',
        'remember_token',
        'action_after_save',
        'status',
    ];
}
