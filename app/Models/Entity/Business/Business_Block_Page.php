<?php
namespace App\Models\Entity\Business;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use App\Models\Entity\Business\Traits\Behavior\BlockPageBehavior;
use App\Models\Entity\Business\Traits\Relationship\BlockPageRelationship;

class Business_Block_Page extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use BlockPageBehavior;
    use BlockPageRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'block_page';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'page_code';
    
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
        'page_code',
        'page_name',
        'page_layout',
        'page_url',
        'language_id',
    ];
}
