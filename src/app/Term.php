<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use Models\Serial;
    public static $s_prefix = "T";
    public static $s_start = 24300000;
    public static $s_end = 728999999;
}
