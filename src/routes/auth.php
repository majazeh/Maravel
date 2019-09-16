<?php

Route::get('/login', 'LoginController@showLoginForm')->name('login');
Route::post('/login', 'LoginController@login');
Route::post('/logout', 'LoginController@logout')->name('logout');

Route::get('/register', 'LoginController@showRegistrationForm')->name('register');

Route::get('verify/{token}', 'LoginController@emailVerify')->name('emailVerify');
Route::get('/login/google', 'LoginController@redirectToProvider');
Route::get('/oauth2callback', 'LoginController@handleProviderCallback');
Route::get('/login/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/login/{token}', 'ResetPasswordController@iReset')->name('password.reset');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    // $query = http_build_query([
    //     'grant_type' => 'authorization_code',
    //     'client_id' => 5,
    //     'redirect_uri' => 'http://dashboard.local/redirect',
    //     'response_type' => 'token',
    //     'scope' => '',
    //     'state' => $state,
    // ]);
    $query = http_build_query([
        'client_id' => '5',
        'redirect_uri' => 'http://dashboard.local/callback',
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect('http://dashboard.local/oauth/authorize?'.$query);
});

Route::get('/callback', function (Request $request) {
    $state = $request->session()->pull('state');

    throw_unless(
        strlen($state) > 0 && $state === $request->state,
        InvalidArgumentException::class
    );

    $http = new GuzzleHttp\Client;

    $response = $http->post('http://dashboard.local/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'redirect_uri' => 'http://dashboard.local/callback',
            'code' => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

Route::get('/token', function (Request $request) {
    $user = App\User::find(1);

// Creating a token without scopes...
    $token = $user->createToken('Token Name')->accessToken;
    Cookie::queue('maravel-token', $token, 45000);

    return $token;
    return Cookie::get('laravel_token');
});

Route::get('xxxxx', function(){
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://dashboard.local/oauth/token', [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => '7',
            'client_secret' => '81tIpH55FgQkK8AsUbvVzMsf07ql7KZOlEYrx18J',
            'username' => 'manager',
            'password' => '111111',
            'scope' => '',
        ],
    ]);
        // dd(json_decode((string) $response->getBody(), true));
    return json_decode((string) $response->getBody(), true);
});
