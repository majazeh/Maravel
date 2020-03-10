<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Term;

class _Term extends Model
{
    public $guarded = [];
    use Serial;
    public static $s_prefix = "T";
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    const MAX_LEVEL = 6;

    public $with = ['parents', 'creator'];
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if($model->parent_id)
            {
                $parents = static::find($model->parent_id)->parent_map ?: [];
                $parents[] = $model->parent_id;
                $model->parent_map = $parents;
            }
        });
    }

    public function parents()
    {
        $hasMany = new ManyParentMap(
            $this->newRelatedInstance(Term::class)->newQuery(),
            $this,
            'terms.id',
            'parent_id');
            $hasMany->without(...$this->with);
            return $hasMany;
    }

    public function getParentMapAttribute()
    {
        if(isset($this->attributes['parent_map'])){
            $parents = explode(':', $this->attributes['parent_map']);
            if(!$parents[0])
            {
                array_shift($parents);
            }
            return array_map(function($id){
                return (int) $id;
            }, $parents);
        }
        return null;
    }

    public function setParentMapAttribute($value)
    {
        $this->attributes['parent_map']  = is_array($value) ? ':' . join(':', $value) : $value;
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'creator_id');
    }
}
