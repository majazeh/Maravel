<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Requests\Maravel as Request;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user, Request $request)
    {
        return true;
    }

    public function view(User $user, Request $request, User $show)
    {
        return true;
    }

    public function update(User $user, Request $request, User $show)
    {
        return true;
    }

    public function create(User $user, Request $request)
    {
        return true;
    }

    public function delete(User $user, Request $request, User $show)
    {
        return true;
    }
}
