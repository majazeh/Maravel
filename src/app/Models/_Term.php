<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class _Term extends Model
{
    public $guarded = [];
    use Serial;
    public static $s_prefix = "T";
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $_parents;

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if($model->parent_id)
            {
                $model->parent_map = static::find($model->parent_id)->parent_map . ':' . $model->parent_id;
            }
        });
    }

    public function getParentsAttribute(){
        if($this->_parents)
        {
            return $this->_parents;
        }
        if ($this->parent_map) {
            $parents = static::whereIn('id', explode(':', $this->parent_map))->orderBy('parent_id', 'asc')->get();
            $this->_parents = empty($parents) ? null : $parents;
            return $this->_parents;
        }
        return null;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
