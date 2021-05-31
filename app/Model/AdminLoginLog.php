<?php

declare (strict_types=1);
namespace App\Model;

use App\Model\Reuse\TraitModel;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property int $admin_id 
 * @property string $ip 
 * @property string $addr 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class AdminLoginLog extends Model
{
    use SoftDeletes;
    use TraitModel;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_login_logs';
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
    protected $casts = ['id' => 'integer', 'admin_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}