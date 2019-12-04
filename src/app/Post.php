<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent
{
    use Models\Serial;

    protected $guarded = [
        'id'
    ];


    public static $s_prefix = 'P';
    public static $s_start = 729000000;
    public static $s_end = 21869999999;

    protected $hidden = [

    ];
    protected $casts = [
        'meta' => 'array',
        'published_at' => 'datetime',
    ];

    public function attachments()
    {
        return $this->hasMany(File::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
