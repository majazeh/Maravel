<?php
namespace App\EnterTheory;

use Illuminate\Http\Request;
use App\User;
use App\EnterTheory;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class Auth extends Theory
{
    public function boot(Request $request)
    {
        if (auth()->check())
        {

            if($this->model->parent)
            {
                $result = $this->model->parent->theory->run($request);
                $this->model->delete();
                return $result;
            }
            return $this->pass($request);
        }

        if(!$this->user_id && !$this->model->trigger)
        {
            return $this->model;
        }

        if($this->model->user_id && $this->model->user->status != 'active')
        {
            return $this->create($request, 'mobileCode', ['verify_id' => $this->model->user_id]);
        }
        return $this->trigger($request);
    }
    public function passed(Request $request)
    {
        if(auth()->check())
        {
            return auth()->user();
        }
        return auth()->loginUsingId($this->model->user_id);
    }
    public function register(Request $request, EnterTheory $model, array $parameters = [])
    {
        if (auth()->check() && $model->trigger instanceof Auth)
        {
            return $model->theory->passed($request);
        }
        if($model->id && $find = EnterTheory::where('parent_id', $model->id)->where('theory', 'auth')->where('expired_at', '>', Carbon::now())->first())
        {
            return $find;
        }

        return EnterTheory::create([
            'key' => EnterTheory::tokenGenerator(),
            'user_id' => isset($parameters['user_id']) ? $parameters['user_id'] : null,
            'theory' => 'auth',
            'type' => 'temp',
            'parent_id' => isset($parameters['user_id']) ? null : $model->id,
            'expired_at' => isset($parameters['user_id']) ? Carbon::now()->addMinutes(1) : Carbon::now()->addMinutes(10),
            'meta' => isset($parameters['meta']) ? $parameters['meta'] : null
            ]);
    }

    public function response()
    {
        if($this->result instanceof User)
        {
            $auth = app('request')->header('authorization');
            $token = strtolower(substr($auth, 0, 7)) == 'bearer ' ? substr($auth, 7) : $this->result->createToken('api')->accessToken;
            $data = [
                'token' => $token,
            ];
            if(request()->callback && $callback = EnterTheory::where('key', request()->callback)->where('expired_at', '>', Carbon::now())->first())
            {
                $data['key'] = request()->callback;
                $data['theory'] = $callback->getOriginal('theory');
            }
            return [$this->result, $data];
        }
        elseif($this->result instanceof EnterTheory && $this->result->getOriginal('theory') == 'auth')
        {
            return [
                'theory' => 'auth',
                ($this->user_id ? 'key' : 'callback') => $this->result->key
            ];
        }
        return $this->response();
    }

    public function rules(Request $request)
    {
        if(!$this->user_id && !$this->model->getOriginal('trigger') && $this->model->parent)
        {
            return $this->model->parent->theory->rules($request);
        }
        return [];
    }
}
