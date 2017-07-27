<?php
namespace App\Models\Entity\Business;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use App\Models\Entity\Business\Traits\Behavior\BlockTemplateBehavior;
use App\Models\Entity\Business\Traits\Relationship\BlockTemplateRelationship;

class Business_Block_Template extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use BlockTemplateBehavior;
    use BlockTemplateRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'block_template';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'template_id';
    
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
        'template_name',
        'template_content',
        'template_view',
        'template_thumbnail',
        'template_params',
        'template_area',
        'template_type',
    ];
}
