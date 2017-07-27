<?php
namespace App\Models\Entity\Business;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use App\Models\Entity\Business\Traits\Behavior\BlockWidgetBehavior;
use App\Models\Entity\Business\Traits\Relationship\BlockWidgetRelationship;

class Business_Block_Widget extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use BlockWidgetBehavior;
    use BlockWidgetRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'block_widget';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'widget_id';
    
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
        'widget_config',
        'widget_order',
        'widget_area',
        'widget_type',
        'page_code',
        'template_id',
        'function_id',
        'status',
    ];
}
