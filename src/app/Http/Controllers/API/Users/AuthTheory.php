<?php

namespace App\Http\Controllers\API\Users;

use App\Requests\Maravel as Request;
use Illuminate\Validation\ValidationException;
use App\EnterTheory;
use App\EnterTheory\Fake;
use App\Http\Resources\User as ResourcesUser;
use App\User;

trait AuthTheory {
    public function theory(Request $request, EnterTheory $enterTheory)
    {
        return $enterTheory->theory->run($request)->response();
    }

    public function auth(Request $request)
    {
        $enterTheory = EnterTheory::where('key', $request->authorized_key)->first();
        if (!$enterTheory) {
            throw ValidationException::withMessages([
                "authorized_key" => __('auth.key')
            ]);
        }
        return $enterTheory->theory->run($request)->response();
    }

    public function register(Request $request)
    {
        $theory = new Fake;
        return $theory->create($request, 'register', $request->all(array_keys($this->rules($request, 'register'))))->response();
    }

    public function verification(Request $request)
    {
        $enterTheory = EnterTheory::where([
            'key' => $request->mobile,
            'theory' => 'register',
        ])->first();
        if($enterTheory)
        {
            return $enterTheory->theory->run($request)->response();
        }
        abort(404);
    }

    public function recovery(Request $request)
    {
        $enterTheory = EnterTheory::where([
            'key' => $request->mobile,
            'theory' => 'auth',
        ])->first();
        if ($enterTheory) {
            return $enterTheory->theory->create($request, 'recovery')->response();
        }
        abort(404);
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        return [];
    }
    public function authBack(Request $request)
    {
        auth()->user()->token()->revoke();
        if(isset(auth()->user()->token()->meta['admin_id']))
        {
            $user = User::find(auth()->user()->token()->meta['admin_id']);
            $token = $user->createToken('api');
            $token->token->save();
            return [
                'data' => new ResourcesUser($user),
                'token' => $token->accessToken
            ];
        }

        return [];
    }

    public function authAs(Request $request, User $user)
    {
        if($user->type == 'admin')
        {
            throw ValidationException::withMessages([
                "user" => __('admin type is invalid')
            ]);
        }
        $current = auth()->user();
        auth()->user()->token()->revoke();
        $token = $user->createToken('api');
        $token->token->meta = ['admin_id' => auth()->id()];
        $token->token->save();
        return [
            'data' => new ResourcesUser($user),
            'token' => $token->accessToken,
            'current' => $current
        ];

    }
}
