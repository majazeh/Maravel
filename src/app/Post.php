<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent
{
    use Models\Model;
    use Models\Serial;

    protected $guarded = [
        'id','creator_id', 'title', 'content',
        'summary', 'url', 'slug', 'meta',
        'order', 'type', 'parent', 'status',
        'published_at'
    ];


    public static $s_prefix = 'IP';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $hidden = [

    ];
    protected $casts = [

    ];
}
