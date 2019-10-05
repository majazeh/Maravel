<?php

namespace Maravel\Controllers\Auth;

use App\User;
use App\UserSocialNetwork;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class RegisterController extends AuthController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $loginTo = "login";
    public function __construct()
    {
        $this->middleware('guest');
        if(auth()->user())
        {
            $type = auth()->user()->type;
            $this->redirectTo = config("auth.redirects.{$type}_auth",'/dashboard');
            $this->loginTo = config("auth.redirects.{$type}_login",'/login');
        }
    }

    public function register(Request $request)
    {
        $this->username_method($request);
        $UserModel = config('auth.providers.users.model');
        if(!config('auth.enter.register', true) && !$UserModel::count())
        {
            throw ValidationException::withMessages([
                $this->username_method($request) => [_t('auth.register.disabled')],
            ]);
        }
        $this->validator($request)->validate();

        event(new Registered($user = $this->create($request->all())));
        return $this->registered($request, $user)
            ?: redirect($this->loginTo);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(Request $request, $update = false)
    {
        $validation = [
            'email'  => 'required|string|email|max:255|unique:users',
            'mobile' => 'required',
        ];
        $username = $this->username_method($request) == 'username' ? 'email' : $this->username_method($request);

        return Validator::make($request->all(), [
            'password' => 'required|string|min:6',
            $username  => $validation[$username]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $username = $data[$this->username_method];

        $register = $this->user_create([
            'name' => _t('anonymous'),
            'password' => Hash::make($data['password']),
        ], [
            $this->username_method => $username,
        ]);

        if($this->username_method == 'email')
        {
            $token = md5(time() . $username . rand());
            UserSocialNetwork::create([
                'user_id'             => $register->id,
                'social_network'      => 'email',
                'social_network_user' => $username,
                'token'               => $token
            ]);
            \Session::flash('registerMsg', _t('Check your email!'));
            dispatch(new \Majazeh\Maravel\Jobs\SendEmail('emails.verify', ['email' => $username, 'token' => $token, 'title' => _t('register.complate')]));
        }
        return $register;
    }
}
