<?php

namespace App\Policies;

use App\User;
use Illuminate\Http\Request;
use App\Term;

class TermPolicy extends \App\Guardio
{
    public function view(User $user, Request $request, Term $term)
    {
        return true;
    }
    public function viewAny(User $user, Request $request)
    {
        return true;
    }
    public function create(User $user, Request $request)
    {
        return true;
    }
    public function update(User $user, Request $request, Term $term)
    {
        if (!$user->isAdmin() && $term->creator_id != $user->id) {
            return false;
        }
        return true;
    }
    public function delete(User $user, Request $request, Term $term)
    {
        if (!$user->isAdmin() && $term->creator_id != $user->id) {
            return false;
        }
        return true;
    }
}
