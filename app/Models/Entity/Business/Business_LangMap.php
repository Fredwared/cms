<?php
namespace App\Models\Entity\Business;

use App\Models\Entity\Business\Traits\Behavior\LangMapBehavior;
use App\Models\Entity\Business\Traits\Relationship\LangMapRelationship;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\TrackingLog;

class Business_LangMap extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use LangMapBehavior;
    use LangMapRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'langmap';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'langmap_id';
    
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
        'item_module',
        'item_id',
        'language_id',
        'source_item_id',
        'source_language_id',
    ];
}
