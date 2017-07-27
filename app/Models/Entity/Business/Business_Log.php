<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Relationship\LogRelationship;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Entity\Business\Traits\Behavior\LogBehavior;

class Business_Log extends Model
{
    use BasicBehavior;
    use LogBehavior;
    use LogRelationship;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'log_id';

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
        'log_type',
        'model_name',
        'model_id',
        'log_ip',
        'log_url',
        'log_content',
        'log_method',
        'query_time',
        'user_id',
        'user_agent',
        'cookie_val',
    ];
}
