<?php
namespace Maravel\Lib;
class Guardio
{
    static protected $users = [];

    public static function user($user)
    {
        if (!isset(static::$users[$user->id])) {
            static::$users[$user->id] = new GuardioCheck($user);
        }
        return static::$users[$user->id];
    }
    public static function has($access)
    {
        if(!auth()->check())
        {
            return false;
        }
        if (!isset(static::$users[auth()->id()])) {
            static::$users[auth()->id()] = new GuardioCheck(auth()->user());
        }
        return static::$users[auth()->id()]->has($access);
    }
}
