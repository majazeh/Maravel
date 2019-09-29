<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Guard extends Eloquent
{
    protected $guarded = [];
    use Models\Serial;
    public static $s_prefix = 'Guard';
    public static $s_start = 30;
    public static $s_end = 899;

    public function positions()
    {
        return $this->hasMany(GuardPosition::class);
    }
}
