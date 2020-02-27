<?php

namespace App\Http\Controllers\API\Users;

use App\Requests\Maravel as Request;
use App\Guardio;
use App\Token;
use App\User;

trait Methods {
    public function index(Request $request)
    {
        return $this->_index(...func_get_args());
    }

    public function index_query($request)
    {
        $model = $this->model::select('*');
        if(!Guardio::has('view-inactive-user'))
        {
            $model->where('status', 'active');
        }
        return [null, $model];
    }

    public function show(Request $request, User $user)
    {
        return $this->_show(...func_get_args());
    }

    public function store(Request $request)
    {
        // \DB::beginTransaction();
        $user = $this->_store(...func_get_args());
        return $user;
    }

    public function update(Request $request, User $user)
    {
        return $this->_update(...func_get_args());
    }

    public function me(Request $request)
    {
        return $this->show($request, auth()->user());
    }

    public function meUpdate(Request $request)
    {
        return $this->update($request, auth()->user());
    }

    public function changePassword(Request $request)
    {
        $this->update($request, auth()->user());
        $this->revokeAllToken($request);
        $this->statusMessage = 'Password changed';
        return [];

    }

    public function revokeAllToken($request)
    {
        Token::where('user_id', auth()->id())->update(['revoked' => 1]);
    }
}
