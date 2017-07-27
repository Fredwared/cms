<?php
namespace App\Models\Entity\Business;

use App\Models\Traits\BasicBehavior;
use App\Models\Traits\TrackingLog;
use Illuminate\Database\Eloquent\Model;
use App\Models\Entity\Business\Traits\Behavior\ArticleReferenceBehavior;

class Business_Article_Reference extends Model
{
    use BasicBehavior;
    use TrackingLog;
    use ArticleReferenceBehavior;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article_reference';
    
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
        'article_id',
        'reference_id',
    ];
}
