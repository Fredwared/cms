<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Behavior\BuildtopBehavior;
use App\Models\Entity\Business\Traits\Relationship\BuildtopRelationship;
use App\Models\Traits\CustomSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\TrackingLog;

class Business_Buildtop extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use BuildtopBehavior;
    use BuildtopRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buildtop';
    
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
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'category_id',
        'type_id',
        'order',
        'language_id'
    ];
}
