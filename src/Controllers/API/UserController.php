<?php

namespace Maravel\Controllers\API;

use Maravel\Controllers\APIController;
use App\Requests\Maravel as Request;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class UserController extends APIController
{
    public $order_list = ['id', 'name', 'username', 'status', 'type', 'gender', 'daily' => 'created_at'];
    public $username_method;
    public $clientController = \Maravel\Controllers\Dashboard\UserController::class;

    public function index(Request $request)
    {
        return $this->_index($request);
    }

    public function show(Request $request, User $user)
    {
        return $this->_show($request, $user);
    }

    public function store(Request $request)
    {
        if($request->password)
        {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        return $this->_store($request);
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
                    unset($rules['password']);
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

    public function login(Request $request)
	{
        if (!config('auth.enter.login', true)) {
            throw new AuthenticationException('login disabled');
        }
		$this->username_method($request);
		if(!\App\User::where($this->username_method, $request->input($this->username_method))->first() && config('auth.enter.auto_register'))
		{
			return $this->register($request);
		}
		if(\Auth::attempt($this->attempt_rule($request))){
			$user = $this->show($request, \Auth::user());
			if($user->status != 'active')
			{
				throw new AuthenticationException('not active');

			}
            $token = $user->createToken('Android')->accessToken;
            $user->additional(array_merge_recursive($user->additional, [
                'token' => $token
            ]));
            $this->statusMessage = 'succsess';
			return $user;
		}
		else{
			throw new AuthenticationException('Username or password is not match');
		}
	}

	public function attempt_rule(Request $request)
	{
		return [
			$this->username_method => $request->input($this->username_method),
			'password' => $request->input('password')
		];
	}

	public function register(Request $request)
	{
		$this->username_method($request);
		$user = User::where($this->username_method, $request->input($this->username_method))->first();
		if($user)
		{
			return $this->response("user duplicated", null, 401);
		}
		$register = new User;
		$register->password = Hash::make($request->input('password'));
		$register->{$this->username_method} = $request->input($this->username_method);

		if(config('auth.enter.auto_verify'))
		{
			$register->status = 'active';
		}
		$register->type = 'user';
		$register->save();
		if (config('auth.enter.auto_verify'))
		{
            return $this->login($request);
        }
        $this->statusMessage = 'registred';
        return $this->show($request, $this->findOrFail($register->serial ?: $register->id));
	}

	public function me(Request $request)
	{
        $this->statusMessage = 'me';
        if(!\Auth::user())
        {
            throw new AuthenticationException('unauthorized');
        }
		return $this->show($request, \Auth::user());
	}

	public function logout(Request $request)
	{
        $user = $this->show($request, \Auth::user());
        $request->user('api')->token()->revoke();
        $this->statusMessage = 'logout';
		return $user;
	}

	public function username_method(Request $request)
	{
		if($this->username_method) return $this->username_method;
		$username = $request->input('username');
		$type = 'username';
		if(ctype_digit($username)){
			$type = 'mobile';
			$request->request->remove('username');
			$request->request->add([$type => $username]);
		}
		elseif(strpos($username, '@'))
		{
			$type = 'email';
			$request->request->remove('username');
			$request->request->add([$type => $username]);

		}
		$this->username_method = $type;
		return $type;
	}
}
