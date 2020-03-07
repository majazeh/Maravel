<?php

namespace App\Http\Controllers\API\Users;

use App\Requests\Maravel as Request;
use App\Guardio;
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
        $show = $this->show($request, auth()->user());
        $token = auth()->user()->token();
        $show->additional(array_merge_recursive($show->additional, [
            'guards' => Guardio::permissions()
        ]));
        if (isset($token->meta['admin_id'])) {
            $admin = $this->model::findOrFail($token->meta['admin_id']);
            $user = $this->show($request, $admin);
            $show->additional(array_merge_recursive($show->additional, [
                'current' => $user
            ]));
        }
        return $show;
    }

    public function meUpdate(Request $request)
    {
        return $this->update($request, auth()->user());
    }
}