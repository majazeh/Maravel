<?php

namespace App\Http\Controllers\API\Users;

use App\Requests\Maravel as Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Validation\ValidationException;
use App\User;

trait Methods {
    public function show(Request $request, User $user)
    {
        return $this->_show($request, $user);
    }
}
