<?php

namespace App\Policies;

use App\User;
use App\Requests\Maravel as Request;

class UserPolicy extends \App\Guardio
{
    public function viewAny(User $user, Request $request)
    {
        $type = $request->type ?: null;
        if(!$type && !static::has('users.viewAny.all')) {
            return false;
        } else {
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
        if(!$user->isAdmin()) return false;
        return true;
    }

    public function create(User $user, Request $request)
    {
        if (!$user->isAdmin()) return false;
        return true;
    }

    public function delete(User $user, Request $request, User $show)
    {
        if (!$user->isAdmin()) return false;
        return true;
    }
}
