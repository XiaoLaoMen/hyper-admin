<?php

declare (strict_types=1);
namespace App\Model;

use App\Model\Reuse\TraitModel;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property string $url
 * @property string $icon
 * @property int $status
 * @property int $is_default
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class Menu extends Model
{
    use SoftDeletes;
    use TraitModel;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';
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
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'status' => 'integer', 'is_default' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function children() {
        return $this->hasMany(get_class($this), 'pid' ,'id');
    }

    public function child() {
        return $this->children()->with( 'child');
    }

    public function getAll()
    {
        return $this->orderBy('sort','desc')
            ->orderBy('id','asc')
            ->get();
    }

    public function getLevel()
    {
        return $this->with('child')
            ->where('pid','0')
            ->orderBy('sort','desc')
            ->orderBy('id','asc')
            ->get();
    }
}
