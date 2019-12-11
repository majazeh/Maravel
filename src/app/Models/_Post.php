<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;

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

    public function creator()
    {
        return $this->belongsTo(\App\User::class);
    }
}
