<?php
namespace App\Http\Controllers\API;

use App\Requests\Maravel as Request;
use Illuminate\Support\Facades\Hash;
use App\Guardio;
use Illuminate\Support\Facades\Cache;
use App\User;

class _UserController extends Controller
{
    use Users\Auth;
    use Users\Methods;
    public $order_list = ['id'];
    public function gate(Request $request, $action, $arg = null)
    {
        if($action == 'register' && (!config('auth.registration', true) || auth()->check()))
        {
            return false;
        }
        elseif ($action == 'login' && (!config('auth.login', true) || auth()->check()))
        {
            return false;
        }
        elseif (in_array($action, ['verification', 'verify']) && (!config('auth.verification', true) || auth()->check())) {
            return false;
        }
        elseif ($action == 'loginKey' && (!config('auth.login', true) || auth()->check()))
        {
            return false;
        }

        if(in_array($action, ['loginKey', 'verify', 'resetPassword']))
        {
            $parse = Cache::getJson($arg);
            if(!$parse || !User::find(User::encode_id($parse->user)))
            {
                return false;
            }
        }

        if($action == 'show')
        {
            if($arg->status != 'active')
            {
                if(!Guardio::has('guardio', 'view-inactive-user'))
                {
                    return false;
                }
            }
        }
        return true;
    }

    public function rules(Request $request, $action, $user = null)
    {
        // rules of register
        $primaryStore = [
            'gender' => 'nullable|in:male,female',
            'mobile' => 'required|mobile|unique:users',
            'username' => 'nullable|string|unique:users||min:4|max:24',
            'email' => 'nullable|email|unique:users',
            'name' => 'nullable|string',
            'password' => 'nullable|string|min:6|max:24',
            'birthday' => 'nullable|date_format:Y-m-d',
            'degree' => 'nullable',
        ];
        switch ($action) {
            case 'register':
                return array_replace($primaryStore, [
                    'password' => 'required|string|min:6|max:24'
                    ]);
            case 'meUpdate':
                $user = auth()->user();
            case 'update':
                return array_replace($primaryStore, [
                    'mobile' => (auth()->user()->isAdmin() ? 'nullable' : 'required').'|mobile|unique:users,mobile,'. $user->id,
                    'username' => 'nullable|string||min:4|max:24|unique:users,username,' . $user->id,
                    'email' => 'nullable|email|unique:users,email,' . $user->id,
                    'status' => 'nullable|in:' . join(',', User::statusList()),
                    'type' => 'nullable|in:' . join(',', User::typeList()),
                ]);
            case 'store':
                return array_replace($primaryStore, [
                    'status' => 'nullable|in:' . join(',', User::statusList()),
                    'type' => 'nullable|in:' . join(',', User::typeList()),
                ]);
                break;
            case 'login':
                return [
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
            case 'forgetPassword':
                return [
                    'username' => 'nullable|string||min:4|max:24',
                    'mobile' => 'nullable|mobile',
                    'email' => 'nullable|email',
                ];
            case 'resetPassword':
                return [
                    'pin' => 'required|string',
                    'password' => 'required|string|min:6|max:24'
                ];
            case 'changePassword':
                return [
                    'current_password' => 'required|string|min:6|max:24',
                    'password' => 'required|string|min:6|max:24|different:current_password'
                ];
            case 'verify':
                return [
                    'pin' => 'required|string',
                ];
            case '_password': return ['password' => 'required|string|min:6|max:24'];
            default:
                return [];
                break;
        }
    }

    public function requestData(Request $request, $action, &$data, $user = null)
    {
        if(in_array($action, ['login', 'verification', 'verify', 'forgetPassword', 'resetPassword']))
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
        if(in_array($action, ['update', 'meUpdate']))
        {
            if(!auth()->user()->isAdmin())
            {
                foreach ($data as $key => $value) {
                    if(!auth()->user()->CanEdit($key))
                    {
                        unset($data[$key]);
                    }
                }
            }
        }
    }

    public function manipulateData(Request $request, $action, &$data, $user = null)
    {
        // hash password
        if(in_array($action, ['register', 'store', 'update', 'resetPassword', 'changePassword']) && isset($data['password']))
        {
            $data['password'] = Hash::make($data['password']);
        }


        // check admin customize for status, types, other...
        if ($action == 'update')
        {
            if(isset($data['status']) && !Guardio::has('assign-status'))
            {
                $data['status'] = $user->status;
            }

            if (isset($data['type']) && !Guardio::has('assign-type')) {
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
            $data['status'] = Guardio::has('assign-status') && isset($data['status']) ? $data['status'] : User::defaultStatus();
            $data['type'] = Guardio::has('assign-type') && isset($data['type']) ? $data['type'] : User::defaultType();
        }
    }

    public function filters($request, $model)
    {
        $current = [
            'status' => User::statusList(),
            'type' => User::typeList(),
            'gender' => ['male', 'female', 'undefined']
        ];
        $filter = [];

        if($request->status && in_array($request->status, $current['status']))
        {
            $model->where('status', $request->status);
            $filter['status'] = $request->status;
        }

        if ($request->type && in_array($request->type, $current['type'])) {
            $model->where('type', $request->type);
            $filter['type'] = $request->type;
        }

        if ($request->gender && in_array($request->gender, $current['gender'])) {
            if($request->gender == 'undefined')
            {
                $model->whereNull('gender');
            }
            else
            {
                $model->where('gender', $request->gender);
            }
            $filter['gender'] = $request->gender;
        }
        return [$current, $filter];
    }
}
