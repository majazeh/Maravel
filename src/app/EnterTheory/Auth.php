<?php
namespace App\EnterTheory;

use App\Http\Resources\User as ResourcesUser;
use Illuminate\Http\Request;
use App\User;
use App\EnterTheory;
use Carbon\Carbon;

class Auth extends Theory
{
    public function passed(Request $request)
    {
        $user = User::find($this->model->value);
        $data = new ResourcesUser($user);
        $token = $user->createToken('api');
        if(isset($this->model->meta['token']))
        {
            $token->token->meta = $this->model->meta['token'];
            $token->token->save();
        }
        if($this->model->expired_at)
        {
            $this->model->delete();
        }

        $data = [
            'data' => $data,
            'token' => $token->accessToken
        ];
        if($request->callback)
        {
            $callback = EnterTheory::where('key', $request->callback)
            ->where('theory', 'auth')
            ->where('expired_at', '>', Carbon::now())
            ->first();
            if($callback)
            {
                dd($callback->parent->theory->run($request));
            }
        }


        return $data;

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
            'value' => isset($parameters['user_id']) ? $parameters['user_id'] : null,
            'theory' => 'auth',
            'parent_id' => isset($parameters['user_id']) ? null : $model->id,
            'expired_at' => isset($parameters['user_id']) ? Carbon::now()->addMinutes(1) : Carbon::now()->addMinutes(10),
            'meta' => isset($parameters['meta']) ? $parameters['meta'] : null
            ]);
    }

    public function toArray($commit)
    {
        if(isset($commit->theory) && $commit->theory instanceof Auth && !$commit->value)
        {
            return [
                'theory' => 'auth',
                'callback' => $commit->key
            ];
        }
        return parent::toArray($commit);
    }

    public function rules(Request $request)
    {
        return [];
    }
}
