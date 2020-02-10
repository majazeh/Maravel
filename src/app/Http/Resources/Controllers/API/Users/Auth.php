<?php

namespace App\Http\Controllers\API\Users;

use App\Requests\Maravel as Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Validation\ValidationException;
use App\User;
use App\AuthConductors;

trait Auth {
    public function login(Request $request)
    {
        $auth = \Auth::attempt($request->all([$request['method'], 'password']));
        if (!$auth) {
            $this->incrementLoginAttempts($request);
            throw ValidationException::withMessages([
                $request->original_method => __('auth.failed')
            ]);
        }
        if (auth()->user()->status != 'active') {
            throw ValidationException::withMessages([
                $request->original_method => __('auth.inactive')
            ]);
        }
        $user = $this->show($request, auth()->user());
        $token = $user->createToken('Android')->accessToken;
        $user->additional(array_merge_recursive($user->additional, [
            'token' => $token
        ]));
        $this->statusMessage = 'succsess';
        return $user;
    }

    public function register(Request $request)
    {
        $this->incrementRegisterAttempts($request);
        $user = $this->_store($request);
        if($request->status == 'awaiting')
        {
            $user->resource->createVerify();
        }
        $this->statusMessage = 'succsess';
        return $user;
    }

    public function verify(Request $request)
    {
        if ($request->method == 'mobile')
        {
            $this->incrementLoginAttempts($request);
            $request->validate($this->rules($request, 'mobileVerify'));
            $user = User::whereMobile($request->mobile)->first();
            if ($user->status != 'awaiting') {
                throw ValidationException::withMessages([
                    $request->original_method => 'not.awaiting'
                ]);
            }
            $trust = $user->AuthVerify()->hasPin('mobile', $user->mobile, $request->pin);
            if($trust)
            {
                $trust->verify();
            }
        }
        $this->statusMessage = 'success';
        return $this->show($request, $user);
    }

    public function verification(Request $request)
    {
        switch ($request->method) {
            case 'mobile':
                $request->validate([
                    'mobile' => 'required|mobile|exists:users'
                ]);
                break;

            default:
                $request->validate([
                    'username' => 'required|string||min:4|max:24'
                ]);
                break;
        }
        if($request->method == 'mobile')
        {
            $user = User::whereMobile($request->mobile)->first();
            if($user->status != 'awaiting')
            {
                throw ValidationException::withMessages([
                    $request->original_method => 'not.awaiting'
                ]);
            }
            $this->incrementVerificationAttempts($request);
            $verification = $user->createVerify();
            $this->statusMessage = 'success';
            return [
                'data' => ['expires_at' => $verification->bridge()->expires_at->timestamp]
            ];
        }
    }

    public function resetPassword(Request $request)
    {
        if ($request->method == 'mobile') {
            $this->incrementLoginAttempts($request);
            $request->validate([
                'mobile' => 'required|mobile|exists:users'
            ]);
            $user = User::whereMobile($request->mobile)->first();
            if ($user->status != 'active') {
                throw ValidationException::withMessages([
                    $request->original_method => 'auth.inactive'
                ]);
            }
            $this->incrementVerificationAttempts($request);
            $verification = $user->resetPassword();
            $this->statusMessage = 'success';
            return [
                'data' => ['expires_at' => $verification->bridge()->expires_at->timestamp]
            ];
        }
    }

    public function changePassword(Request $request)
    {
        if ($request->method == 'mobile') {
            $this->incrementLoginAttempts($request);
            $request->validate($this->rules($request, 'mobileChangePassword'));
            $user = User::whereMobile($request->mobile)->first();
            if ($user->status != 'active') {
                throw ValidationException::withMessages([
                    $request->original_method => 'auth.inactive'
                ]);
            }
            $trust = $user->AuthVerify()->hasPin('reset_password', $user->mobile, $request->pin);
            if ($trust) {
                $trust->verify();
                $user->password = $request->password;
                $user->update();
            }
        }
        $this->statusMessage = 'success';
        return $this->show($request, $user);
    }

    public function enter(Request $request)
    {
        $once_method = false;
        $register = false;

        foreach ($request->all(['username', 'email', 'mobile']) as $key => $value) {
            if($value)
            {
                if($once_method)
                {
                    $register = true;
                    break;
                }
                $once_method = $key;
            }
        }

        $register_filed = $request->all();
        if(isset($register_filed['password']))
        {
            unset($register_filed['password']);
        }
        if (isset($register_filed[$once_method])) {
            unset($register_filed[$once_method]);
        }
        if(!empty($register_filed))
        {
            $register = true;
        }

        if(!$register && $once_method && User::where($once_method, $request->$once_method)->count() == 0)
        {
            $register = true;
        }

        $data = $request->all();
        $this->requestData($request, $register ? 'register' : 'login', $data);
        $request->validate($this->rules($request, $register ? 'register' : 'login'));
        $this->manipulateData($request, $register ? 'register' : 'login', $data);
        $request->replace($data);
        return $register ? $this->register($request) : $this->login($request);
    }

    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    protected function incrementRegisterAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttlRegisterKey($request),
            30
        );
        if ($this->limiter()->tooManyAttempts($this->throttlRegisterKey($request), 5)) {
            throw new TooManyRequestsHttpException();
        }
    }
    protected function throttlRegisterKey(Request $request)
    {
        return Str::lower('register|' . $request->ip());
    }

    protected function incrementVerificationAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttlVerificationKey($request),
            60
        );
        if ($this->limiter()->tooManyAttempts($this->throttlVerificationKey($request), 2)) {
            throw new TooManyRequestsHttpException();
        }
    }
    protected function throttlVerificationKey(Request $request)
    {
        return Str::lower('verification|' . $request->ip());
    }

    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttlLoginKey($request),
            60
        );

        if ($this->limiter()->tooManyAttempts($this->throttlLoginKey($request), 10)) {
            throw new TooManyRequestsHttpException();
        }
    }
    protected function throttlLoginKey(Request $request)
    {
        return Str::lower($request->username . '|' . $request->ip());
    }
}
