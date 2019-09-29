<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class TimerLog extends Eloquent
{
    protected $guarded = [];
    public static function plus ($user_id, $table_name, $cat, $key, $time) {
        \DB::statement("INSERT INTO timer_logs(`user_id`, `table_name`, `cat`, `key`, `time`, `count`) VALUES ('$user_id', '$table_name', '$cat', '$key', '$time', 1) ON DUPLICATE KEY UPDATE `count` = `count` + 1");
    }

    public static function minus ($user_id, $table_name, $cat, $key, $time) {
        \DB::unprepared("UPDATE timer_logs
        SET `count` = `count` - 1
        WHERE `user_id` = $user_id
        AND`table_name` = '$table_name'
        AND `cat` = '$cat'
        AND `key` = '$key'
        AND `time` = '$time';

        DELETE FROM timer_logs
        WHERE `table_name` = '$table_name'
        AND `cat` = '$cat'
        AND `key` = '$key'
        AND `time` = '$time'
        AND `count` = '0'
        ");
    }

    public static function change($user_id, $table_name, $cat, $key, $time, $change)
    {
        \DB::unprepared("UPDATE timer_logs
        SET `count` = `count` - 1
        WHERE `user_id` = $user_id
        AND `table_name` = '$table_name'
        AND `cat` = '$cat'
        AND `key` = '$key'
        AND `time` = '$time';

        DELETE FROM timer_logs
        WHERE `user_id` = $user_id
        AND `table_name` = '$table_name'
        AND `cat` = '$cat'
        AND `key` = '$key'
        AND `time` = '$time'
        AND `count` = '0';

        INSERT INTO timer_logs(`user_id`, `table_name`, `cat`, `key`, `time`, `count`)
        VALUES ('$user_id', '$table_name', '$cat', '$key', '$change', 1)
        ON DUPLICATE KEY UPDATE `count` = `count` + 1
        ");
    }

    public static function regenerate($user_id, $table_name, $select_query, $cat, $key)
    {
        \DB::unprepared("DELETE FROM timer_logs
        WHERE `user_id` = $user_id
        AND `table_name` = '$table_name'
        AND `cat` = '$cat'
        AND `key` = '$key';

        INSERT INTO timer_logs(`user_id`, `table_name`, `cat`, `key`, `time`, `count`) $select_query");
    }
}
