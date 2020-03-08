<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Term;
use App\TermUsage;
class _Post extends Eloquent
{
    use Serial;

    protected $guarded = [
        'id'
    ];


    public static $s_prefix = 'P';
    public static $s_start = 729000000;
    public static $s_end = 21869999999;

    protected $hidden = [];
    protected $casts = [
        'meta' => 'array',
        'published_at' => 'datetime',
    ];

    public function attachments()
    {
        return $this->hasMany(\App\File::class);
    }
    public function terms()
    {
        return $this->hasManyThrough(\App\Term::class, \App\TermUsage::class, 'table_id', 'id', null, 'term_id')->where('term_usages.table_name', 'posts');
    }

    public function creator()
    {
        return $this->belongsTo(\App\User::class);
    }
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->status == 'publish' && !$model->published_at) {
                $model->published_at = Carbon::now();
            }
        });
        static::saved(function ($model) {
            if (isset($model->attributes['primary_term_id'])) {
                if (isset($model->original['primary_term_id'])) {
                    if($model->attributes['primary_term_id'] != $model->original['primary_term_id'])
                    {
                        TermUsage::replace_map($model, $model->original['primary_term_id'], $model->attributes['primary_term_id']);
                    }
                } else {
                    $parents = explode(':', $model->primaryTerm->parent_map);
                    $parents[] = $model->attributes['primary_term_id'];
                    foreach ($parents as $key => $value) {
                        if(!$value) continue;
                        TermUsage::create([
                            'term_id' => $value,
                            'table_name' => 'posts',
                            'table_id' => $model->id,
                        ]);
                    }
                }
            }
            if ($model->status == 'publish' && !$model->published_at) {
                $model->published_at = Carbon::now();
            }
        });
    }

    public function primaryTerm()
    {
        return $this->belongsTo(Term::class);
    }

    public function addTerm(...$args){
        return TermUsage::add($this, ...$args);
    }
}
