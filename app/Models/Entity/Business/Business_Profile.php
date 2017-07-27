<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Behavior\ProfileBehavior;
use App\Models\Entity\Business\Traits\Relationship\ProfileRelationship;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\TrackingLog;

class Business_Profile extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use ProfileBehavior;
    use ProfileRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profile';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'profile_id';
    
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
        'group_id',
        'role_code',
        'menu_id',
    ];
}
