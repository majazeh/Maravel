<?php

namespace Maravel\Controllers\API;

use Maravel\Controllers\APIController;
use App\Requests\Maravel as Request;
use App\User;
use App\File;
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
        $avatar = $request->file('avatar');
        $request->files->remove('avatar');
        $update = $this->_update($request, $user);
        if($avatar)
        {
            $attachment = File::upload($request, 'avatar');
            if($attachment)
            {
                $update->resource->avatar_id = $attachment->id;
                $update->resource->save();
                $this->statusMessage = $this->class_name() . " changed";
            }
            File::imageSize($attachment, 500);
            File::imageSize($attachment, 250);
            File::imageSize($attachment, 150);
        }
        return $update;
    }


    public function destroy(Request $request, User $user)
    {
        return $this->_destroy($request, $user);
    }
    public function except(Request $request, $action)
    {
        switch ($action) {
            case 'update':
            case 'store':
            return ['avatar'];
        }
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
                    'groups' => 'nullable',
                    'avatar' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120|dimensions:ratio=1',
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

    public function requestData(Request $request, $action, &$data)
    {
        if (in_array($action, ['store', 'update']) && isset($data['groups'])) {
            $groups = \App\Guardio::allGroups();
            $parse = [];
            foreach ($data['groups'] as $key => $value) {
                if(in_array($value, $groups))
                {
                    $parse[] = $value;
                }
            }
            $data['groups'] = join('|', $parse);
        }
    }

    public function filters($request, $model, $parent = null)
    {

        $filters = [
            'status' => config('guardio.status', ['awaiting', 'active', 'disable']),
            'type' => config('guardio.type', ['admin', 'user']),
            'gender' => ['male', 'female'],
            'username' => '',
            'q' => null
        ];
        $current = [];
        if ($request->username && $request->has('unique')) {
            $id = User::id($request->user);
            if ($id) {
                $model->where('id', '<>', $id);
            }
            $model->where('username', $request->username)->limit(1);
            return [$filters, ['username' => $request->username]];
        }
        if ($request->email && $request->has('unique')) {
            $id = User::id($request->user);
            if ($id) {
                $model->where('id', '<>', $id);
            }
            $model->where('email', $request->email)->limit(1);
            return [$filters, ['email' => $request->email]];
        }
        if ($request->mobile && $request->has('unique')) {
            list($mobile, $country, $code) = \Maravel\Lib\MobileRV::parse($request->mobile);
            if($mobile)
            {
                $id = User::id($request->user);
                if ($id) {
                    $model->where('id', '<>', $id);
                }
                $model->where('mobile', "Like", "%$mobile%")->limit(1);
                return [$filters, ['mobile' => $request->mobile]];
            }
            else
            {
                $model->limit(0);
            }
        }
        if(in_array($request->status, $filters['status']))
        {
            $model->where('status', $request->status);
            $current['status'] = $request->status;
        }
        if (in_array($request->type, $filters['type'])) {
            $model->where('type', $request->type);
            $current['type'] = $request->type;
        }
        if (in_array($request->gender, $filters['gender'])) {
            $model->where('gender', $request->gender);
            $current['gender'] = $request->gender;
        }
        if ($request->q) {
            $this->searchQ($request, $model, $parent);
            $current['q'] = $request->q;
        }
        return [$filters, $current];
    }

    public function searchQ($request, $model, $parent)
    {
        $q = $request->q;
        $model->where(function($query) use($q){
            $query->where('users.name', 'LIKE', "%$q%")
            ->orWhere('users.username', 'LIKE', "%$q%")
            ->orWhere('users.mobile', 'LIKE', "%$q%")
            ->orWhere('users.nikname', 'LIKE', "%$q%");
        });
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
