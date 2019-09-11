<?php

namespace Maravel\Controllers\API;

use Maravel\Controllers\APIController;
use App\Requests\Maravel as Request;
use App\User;
use Illuminate\Support\Facades\Gate;

class UserController extends APIController
{
    public $order_list = ['id', 'name', 'username', 'status', 'type', 'gender'];

    public $filters = [
        'test' => true
    ];

    public function authorizations($request, $action, User $user = null)
    {
        return true;
    }

    public function index(Request $request)
    {
        // dd(Gate::allows('guardio', 'check'));
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

    public function rules(Request $request, $action)
    {
        switch ($action) {
            case 'update':
            case 'store':
                $rules = [
                    'name' => 'nullable',
                    'username' => 'nullable',
                    'password' => 'nullable|min:6',
                    'email' => 'nullable|email',
                    'status' => 'required|in:'. join(config('guardio.status', ['awaiting', 'active', 'disable']), ','),
                    'type' => 'required|in:'. join(config('guardio.type', ['admin', 'user']), ','),
                    'mobile' => 'nullable|mobile',
                    'gender' => 'nullable|in:male,female'
                ];
                if(!$request->password)
                {
                    $request->request->remove('password');
                }
                return $rules;
                break;
            default:
                return [];
                break;
        }
    }
}
