<?php
namespace App\Models\Entity\Business;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BasicBehavior;
use App\Models\Traits\CustomSoftDeletes;
use App\Models\Traits\TrackingLog;
use App\Models\Entity\Business\Traits\Behavior\ProductBehavior;
use App\Models\Entity\Business\Traits\Relationship\ProductRelationship;

class Business_Product extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use CustomSoftDeletes;
    use ProductBehavior;
    use ProductRelationship;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'product_id';
    
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
        'product_title',
        'product_code',
        'product_description',
        'product_content',
        'thumbnail_url',
        'product_price',
        'warranty_note',
        'amount_inventory',
        'share_url',
        'language_id',
        'category_id',
        'manufacturer_id',
        'link_source',
        'user_id',
        'created_at',
        'updated_at',
        'status',
        'deleted',
    ];
}
