<?php

namespace App\Http\Controllers\API\Users;

use App\Requests\Maravel as Request;
use App\Guardio;
use App\User;

trait Methods {

    public $userCanEdit = [
        'user' => [
            'name', 'email', 'password', 'gender'
        ],
        'client' => [
            'name', 'email', 'password', 'gender'
        ],
        'psychologist' => [
            'name', 'email', 'password', 'gender'
        ],
    ];

    public function index(Request $request)
    {
        return $this->_index($request);
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
        return $this->_show($request, $user);
    }

    public function update(Request $request, User $user)
    {
        return $this->_update($request, $user);
    }

    public function meUpdate(Request $request)
    {
        return $this->update($request, auth()->user());
    }
}
