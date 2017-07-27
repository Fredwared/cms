<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Behavior\TagBehavior;
use App\Models\Entity\Business\Traits\Relationship\TagRelationship;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use Illuminate\Database\Eloquent\Model;

class Business_Tag extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use TagBehavior;
    use TagRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tag';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'tag_id';
    
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
        'tag_name',
        'language_id',
        'status',
    ];
}
