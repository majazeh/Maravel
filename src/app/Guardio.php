<?php
namespace App;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\HandlesAuthorization;
use \Illuminate\Auth\Access\AuthorizationException;
class Guardio
{
    use HandlesAuthorization;
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

    public static function gates()
    {
        $laravelGates = array_keys(Gate::abilities());
        $configGates = config('guardio.gates');
        return array_merge_recursive($laravelGates, $configGates);
    }

    public static function permissions($key = false)
    {
        return static::user(auth()->user())->permissions($key);
    }
    public static function get($key)
    {
        return static::user(auth()->user())->get($key);
    }

    public static function users()
    {
        return static::$users;
    }

    protected function denyAccess($message = 'This action is unauthorized.')
    {
        abort(403, $message);
    }
}
