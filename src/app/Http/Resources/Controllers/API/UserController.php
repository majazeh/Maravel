<?php
namespace App\Http\Controllers\API;

use App\Requests\Maravel as Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use App\User;

class UserController extends Controller
{
    use Users\Auth;
    use Users\Methods;
    public function gate(Request $request, $action)
    {
        if($action == 'register' && (!config('auth.registration', true) || auth()->check()))
        {
            return false;
        }
        elseif ($action == 'login' && (!config('auth.login', true) || auth()->check()))
        {
            return false;
        }
        elseif ($action == 'verification' && (!config('auth.verification', true) || auth()->check())) {
            return false;
        }
        return true;
    }

    public function rules(Request $request, $action)
    {
        // rules of register
        $primaryStore = [
            'gender' => 'nullable|in:male,female',
            'mobile' => 'required|mobile|unique:users',
            'username' => 'nullable|string|unique:users||min:4|max:24',
            'email' => 'nullable|email|unique:users',
            'name' => 'nullable|string',
            'password' => 'nullable|string|min:6|max:24'
        ];
        switch ($action) {
            case 'register':
                return array_replace_recursive($primaryStore, [
                    'password' => 'required|string|min:6|max:24'
                ]);;
            case 'store':
                return array_replace_recursive($primaryStore, [
                    'status' => 'nullable|in:' . join(',', User::statusList()),
                    'type' => 'nullable|in:' . join(',', User::typeList()),
                ]);
                break;
            case 'login':
                return [
                    'password' => 'required|string|min:6|max:24',
                    'username' => 'nullable|string||min:4|max:24|oneOf:email,mobile',
                    'mobile' => 'nullable|mobile',
                    'email' => 'nullable|email',
                ];
            case 'enter':
                return [
                    'gender' => 'nullable|in:male,female',
                    'mobile' => 'nullable|mobile',
                    'username' => 'nullable|string||min:4|max:24',
                    'email' => 'nullable|email',
                    'name' => 'nullable|string',
                    'password' => 'required|string|min:6|max:24'
                ];
            case 'verification':
            case 'verify':
            case 'resetPassword':
                return [
                    'username' => 'nullable|string||min:4|max:24',
                    'mobile' => 'nullable|mobile',
                    'email' => 'nullable|email',
                ];
            case 'changePassword':
                return [
                    'username' => 'nullable|string||min:4|max:24',
                    'mobile' => 'nullable|mobile',
                    'email' => 'nullable|email',
                    'password' => 'required|string|min:6|max:24'
                ];
            case 'mobileChangePassword':
            case 'mobileVerify':
                return [
                    'mobile' => 'required|mobile|exists:users',
                    'pin' => 'required|string',
                ];
            default:
                return [];
                break;
        }
    }

    public function requestData(Request $request, $action, &$data)
    {
        if(in_array($action, ['login', 'verification', 'verify', 'resetPassword', 'changePassword']))
        {
            $data['method'] = 'username';
            $data['original_method'] = 'username';
            if(isset($data['username']))
            {
                $username = $data['username'];
                if ((substr($data['username'], 0, 1) == '+' && ctype_digit(substr($data['username'], 1))) || ctype_digit($data['username']))
                {
                    $data['method'] = 'mobile';
                    unset($data['username']);
                    $data['mobile'] = $username;
                }
                elseif (strstr($data['username'], '@'))
                {
                    $data['method'] = 'email';
                    unset($data['username']);
                    $data['email'] = $username;
                }
            }
            elseif(isset($data['mobile']))
            {
                $data['method'] = 'mobile';
                $data['original_method'] = 'mobile';
            }
            elseif (isset($data['email']))
            {
                $data['method'] = 'email';
                $data['original_method'] = 'email';
            }

        }
    }

    public function manipulateData(Request $request, $action, &$data, $user = null)
    {
        // hash password
        if(in_array($action, ['register', 'store', 'update', 'changePassword']) && isset($data['password']))
        {
            $data['password'] = Hash::make($data['password']);
        }

        // check admin customize for status, types, other...
        if ($action == 'update')
        {
            if(isset($data['status']) && !Gate::has('assign-status'))
            {
                $data['status'] = $user->status;
            }

            if (isset($data['type']) && !Gate::has('assign-type')) {
                $data['type'] = $user->type;
            }

        }
        elseif ($action == 'register')
        {
            if (User::count() == 0)
            {
                $data['status'] = 'active';
                $data['type'] = 'admin';
            }
            else
            {
                $data['status'] = User::defaultStatus();
                $data['type'] = User::defaultType();
            }
        }
        elseif($action == 'store')
        {
            $data['status'] = !Gate::has('assign-status') || isset($data['status']) ? User::defaultStatus() : $data['status'];
            $data['type'] = !Gate::has('assign-type') || isset($data['type']) ? User::defaultType() : $data['type'];
        }
    }
}
