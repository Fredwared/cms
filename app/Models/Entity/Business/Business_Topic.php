<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Behavior\TopicBehavior;
use App\Models\Entity\Business\Traits\Relationship\TopicRelationship;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use Illuminate\Database\Eloquent\Model;

class Business_Topic extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use TopicBehavior;
    use TopicRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'topic';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'topic_id';
    
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
        'topic_title',
        'category_id',
        'language_id',
        'max_article',
        'status',
    ];
}
