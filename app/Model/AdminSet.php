<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;
use App\Model\Reuse\TraitModel;

/**
 * @property int $id 
 * @property string $key 
 * @property string $desc 
 * @property string $val 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class AdminSet extends Model
{
    use SoftDeletes;
    use TraitModel;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_sets';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}