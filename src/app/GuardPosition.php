<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GuardPosition extends Eloquent
{
    protected $guarded = [];
    use Models\Serial;
    public static $s_prefix = 'GuardP';
    public static $s_start = 900;
    public static $s_end = 26999;
}
