<?php

namespace App\Policies;

use App\User;
use Illuminate\Http\Request;

class UserPolicy extends \App\Guardio
{
    public function viewAny(User $user, Request $request)
    {
        $type = $request->type ?: null;
        if(!$type && !static::has('users.viewAny.all')) {
            return false;
        } else {
            if(is_array($request->type))
            {
                $allowd_count = 0;
                foreach ($request->type as $key => $value) {
                    if(static::has('users.viewAny.' . $value))
                    {
                        $allowd_count++;
                    }
                }
                if($allowd_count == 0) return false;
                return true;
            }
            return static::has('users.viewAny.'.$type);
        }
    }

    public function view(User $user, Request $request, User $show)
    {
        if($user->id == $show->id || static::has('users.viewAny.all'))
        {
            return true;
        } else {
            return static::has('users.viewAny.' . $show->type);
        }
    }

    public function update(User $user, Request $request, User $show)
    {
        if(!$user->isAdmin() && !$user->idIs($show)) return false;
        return true;
    }

    public function create(User $user, Request $request)
    {
        if (!static::has('users.create')) return false;
        return true;
    }

    public function delete(User $user, Request $request, User $show)
    {
        if (!$user->isAdmin()) return false;
        return true;
    }

    public function isAdmin(User $user, Request $request)
    {
        return $user->isAdmin();
    }
}
