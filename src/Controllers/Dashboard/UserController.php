<?php

namespace Maravel\Controllers\Dashboard;

use Maravel\Controllers\WebController;
use App\Requests\Maravel as Request;
use App\User;
use Maravel\Controllers\API\UserController as API;
use Maravel\Lib\Guardio;
class UserController extends WebController
{
    public $endpoint = API::class;
    public function index(Request $request)
    {
        return $this->_index($request);
    }

    public function show(Request $request, User $user)
    {
        return $this->_show($request, $user);
    }

    public function create(Request $request)
    {
        return $this->_create($request);
    }

    public function store(Request $request)
    {
        return $this->_store($request);
    }

    public function edit(Request $request, User $user)
    {
        return $this->_edit($request, $user);
    }

    public function update(Request $request, User $user)
    {
        return $this->_update($request, $user);
    }

    public function destroy(Request $request, User $user)
    {
        return $this->_destroy($request, $user);
    }
}
