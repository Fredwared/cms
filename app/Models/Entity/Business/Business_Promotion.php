<?php
namespace App\Models\Entity\Business;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use App\Models\Entity\Business\Traits\Behavior\PromotionBehavior;
use App\Models\Entity\Business\Traits\Relationship\PromotionRelationship;

class Business_Promotion extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use PromotionBehavior;
    use PromotionRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotion';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'promotion_id';
    
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
        'promotion_title',
        'promotion_code',
        'promotion_content',
        'sale_percent',
        'available_from',
        'available_to',
        'language_id',
        'status',
    ];
}
