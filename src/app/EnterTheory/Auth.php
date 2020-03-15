<?php
namespace App\EnterTheory;

use App\Http\Resources\User as ResourcesUser;
use Illuminate\Http\Request;
use App\User;
use App\EnterTheory;
use Carbon\Carbon;

class Auth extends Theory
{
    public function boot(Request $request)
    {
        if (auth()->check())
        {
            if($this->model->parent)
            {
                $result = $this->model->parent->theory->run($request);
                // $this->model->delete();
                return $result;
            }
            return $this->pass($request);
        }
        if(!$this->user_id && !$this->model->trigger)
        {
            return $this->model;
        }

        return $this->trigger($request);
    }
    public function passed(Request $request)
    {
        $this->callback = $request->callback;
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
        if($model->id && $find = EnterTheory::where('parent_id', $model->id)->where('theory', 'auth')->first())
        {
            return $find;
        }
        return EnterTheory::create([
            'key' => EnterTheory::tokenGenerator(),
            'user_id' => isset($parameters['user_id']) ? $parameters['user_id'] : null,
            'theory' => 'auth',
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
                'data' => new ResourcesUser($this->result),
                'token' => $token
            ];
            if($this->callback)
            {
                $data['key'] = $this->callback;
            }
            return $data;
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
