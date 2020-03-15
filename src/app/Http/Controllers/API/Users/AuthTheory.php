<?php

namespace App\Http\Controllers\API\Users;

use App\Requests\Maravel as Request;
use Illuminate\Validation\ValidationException;
use App\EnterTheory;
use App\EnterTheory\Fake;
use App\User;

trait AuthTheory {
    public function theory(Request $request, EnterTheory $enterTheory)
    {
        return $enterTheory->theory->run($request)->response();
    }

    public function login(Request $request)
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

    public function forgetPassword(Request $request)
    {
        $enterTheory = EnterTheory::where([
            'key' => $request->mobile,
            'theory' => 'auth',
        ])->first();
        if ($enterTheory) {
            return $enterTheory->theory->create($request, 'forgetPassword')->response();
        }
        abort(404);
    }

    public function logout(Request $request)
    {
        dd($request->route());
        auth()->user()->token()->revoke();
        return [];
    }
    public function logoutBack(Request $request)
    {
        if(isset(auth()->user()->token()->meta['admin_id']))
        {
            $theory = new Fake;
            return $theory->create($request, 'auth', [
                'user_id' => auth()->user()->token()->meta['admin_id']
            ])->response();
        }
        auth()->user()->token()->revoke();

        return [];
    }

    public function loginAs(Request $request, User $user)
    {
        if($user->type == 'admin')
        {
            throw ValidationException::withMessages([
                "user" => __('admin type is invalid')
            ]);
        }
        auth()->user()->token()->revoke();
        $theory = new Fake;
        return $theory->create($request, 'auth', [
            'user_id' => $user->id,
            'meta' => [
                'token' => ['admin_id' => auth()->id()]
            ]
        ])->response();
    }
}
