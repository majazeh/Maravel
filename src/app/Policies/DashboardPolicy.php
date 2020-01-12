<?php

namespace App\Policies;

use App\User;
use App\Requests\Maravel as Request;

class DashboardPolicy extends \App\Guardio
{
    public function view(User $user)
    {
        return static::has('dashboard.view');
    }
}
