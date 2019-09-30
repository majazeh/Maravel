<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model as Eloquent;

class File extends Eloquent
{
    use Models\Model;
    use Models\Serial;

    protected $guarded = [
        'id', 'slug', 'dir', 'mood', 'group', 'mime', 'exec', 'created_at', 'updated_at'
    ];


    public static $s_prefix = 'IF';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $hidden = [

    ];
    protected $casts = [
        'mood' => 'array',
    ];

}
