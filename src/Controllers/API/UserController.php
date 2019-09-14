<?php

namespace Maravel\Controllers\API;

use Maravel\Controllers\APIController;
use App\Requests\Maravel as Request;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends APIController
{
    public $order_list = ['id', 'name', 'username', 'status', 'type', 'gender'];

    public $filters = [
        'test' => true
    ];

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
        if($request->password)
        {
            $request->replace(['password' => Hash::make($request->password)]);
        }
        return $this->_store($request);
    }

    public function edit(Request $request, User $user)
    {
        return $this->_edit($request, $user);
    }

    public function update(Request $request, User $user)
    {
        if ($request->password) {
            $request->replace(['password' => Hash::make($request->password)]);
        }
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
                    'username' => 'OneOf:email,mobile',
                    'password' => 'nullable|min:6',
                    'email' => 'nullable|email',
                    'status' => 'required|in:'. join(config('guardio.status', ['awaiting', 'active', 'disable']), ','),
                    'type' => 'required|in:'. join(config('guardio.type', ['admin', 'user']), ','),
                    'mobile' => 'nullable|mobile',
                    'gender' => 'nullable|in:male,female',
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

    public function filters($request, $model, $parent = null)
    {

        $filters = [
            [
                'status' => config('guardio.status', ['awaiting', 'active', 'disable']),
                'type' => config('guardio.type', ['admin', 'user']),
                'gender' => ['male', 'female']
            ]
        ];
        $current = [];
        if(in_array($request->status, $filters[0]['status']))
        {
            $model->where('status', $request->status);
            $current['status'] = $request->status;
        }
        if (in_array($request->type, $filters[0]['type'])) {
            $model->where('type', $request->type);
            $current['type'] = $request->type;
        }
        if (in_array($request->gender, $filters[0]['gender'])) {
            $model->where('gender', $request->gender);
            $current['gender'] = $request->gender;
        }
        return [$filters, $current];
    }
}
