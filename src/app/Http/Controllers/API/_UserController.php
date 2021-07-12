<?php
namespace App\Http\Controllers\API;

use App\Requests\Maravel as Request;
use Illuminate\Support\Facades\Hash;
use App\Guardio;
use App\User;
use Illuminate\Validation\Rule;
use Maravel\Lib\MobileRV;

class _UserController extends Controller
{
    use Users\AuthTheory;
    use Users\Methods;
    public $order_list = ['id'];
    public function gate(Request $request, $action, $arg = null)
    {
        if($action == 'register' && (!config('auth.registration', true) || auth()->check()))
        {
            return false;
        }
        elseif ($action == 'auth' && !config('auth.login', true))
        {
            return false;
        }
        elseif (in_array($action, ['verification', 'verify']) && (!config('auth.verification', true) || auth()->check())) {
            return false;
        }
        elseif ($action == 'theory' && !config('auth.login', true))
        {
            return false;
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
            'name' => 'nullable|string',
            'password' => 'nullable|string|min:6|max:24',
            'birthday' => 'nullable|date_format:Y-m-d',
            'gender' => 'nullable|in:male,female'
        ];
        switch ($action) {
            case 'auth':
                return [
                    'authorized_key' => 'required|min:4|max:110'
                ];
            case 'theory' :
                return $user->theory->rules($request);
            case 'register':
                return array_replace($primaryStore, [
                    'mobile' => [
                        'required',
                        'mobile',
                        Rule::unique('users', 'mobile')->whereNot('status', 'awaiting')
                    ]
                    ]);
            case 'meUpdate':
                $user = auth()->user();
            case 'update':
                return array_replace($primaryStore, [
                    'mobile' => (auth()->user()->isAdmin() || !$request->has('mobile') ? 'nullable' : 'required').'|mobile|unique:users,mobile,'. $user->id,
                    'username' => 'nullable|'. (auth()->user()->isAdmin() ? 'string' : 'alpha_num') .'||min:4|max:24|unique:users,username,' . $user->id,
                    'email' => 'nullable|email|unique:users,email,' . $user->id,
                    'status' => 'nullable|in:' . join(',', User::statusList()),
                    'type' => 'nullable|in:' . join(',', User::typeList()),
                ]);
            case 'store':
                return array_replace($primaryStore, [
                    'mobile' => (auth()->user() && auth()->user()->isAdmin() ? 'nullable' : 'required') . '|mobile|unique:users,mobile',
                    'username' => 'nullable|'. (auth()->user() && auth()->user()->isAdmin() ? 'string' : 'alpha_num') .'|unique:users||min:4|max:24',
                    'email' => 'nullable|email|unique:users',
                    'status' => 'nullable|in:' . join(',', User::statusList()),
                    'type' => 'nullable|in:' . join(',', User::typeList()),
                ]);
                break;
            case 'changePassword' :
                return [
                    'password' => [
                        (auth()->isAdmin() ? 'nullable' : 'required'),'string','min:6','max:24',
                        function($name, $value, $fail) use ($user, $request){
                            if(!Hash::check($request->password, $user->password)){
                                $fail(__('validation.password-match'));
                            }
                            
                        }
                    ],
                    'new_password' => ['required', 'string', 'min:6','max:24',
                        function($name, $value, $fail) use ($user, $request){
                            if (Hash::check($request->new_password, $user->password)) {
                                $fail(__('validation.password-old'));
                            }
                        }
                ],
                ] ;
            case 'avatar':
                return [
                    'avatar' => 'required|mimes:jpeg,jpg,png|dimensions:ratio=1|max:2048'
                ];
            case 'verification':
                return [
                    'mobile' => 'required|mobile|exists:users,mobile,status,awaiting',
                ];
            case 'recovery':
                return [
                    'mobile' => 'required|mobile|exists:users,mobile,status,active',
                ];
            default:
                return [];
                break;
        }
    }

    public function requestData(Request $request, $action, &$data, $user = null)
    {
        if(in_array($action, ['auth', 'verification', 'recovery']))
        {
            $data['method'] = 'username';
            $data['original_method'] = 'username';
            if(isset($data['username']))
            {
                $username = $data['username'];
                if (MobileRV::parse($data['username']))
                {
                    $data['method'] = 'mobile';
                    list($mobile, $c, $code) = MobileRV::parse($data['username']);
                    unset($data['username']);
                    $data['mobile'] = "+$code$mobile";
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
            if($action == 'index')
            {
                if(isset($data['type']) && is_array($data['type']))
                {
                    $types = [];
                    foreach ($data['type'] as $key => $value) {
                        if (Guardio::has('users.viewAny.' . $value)) {
                            $types[] = $value;
                        }
                    }
                    $data['type'] = $types;
                }
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
        if($action == 'auth' && isset($data['authorized_key']))
        {
            if ($mobile = MobileRV::parse($data['authorized_key'])) {
                $data['authorized_key'] = $mobile[2] . $mobile[0];
            }
        }
        // hash password
        if(in_array($action, ['register', 'store']) && isset($data['password']))
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
            'gender' => ['male', 'female', 'undefined'],
            'q' => null
        ];
        $filter = [];

        if($request->status && in_array($request->status, $current['status']))
        {
            $model->where('status', $request->status);
            $filter['status'] = $request->status;
        }

        if ($request->type) {
            if(is_array($request->type))
            {
                $model->whereIn('type', $request->type);
            }
            else
            {
                $model->where('type', $request->type);
            }
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

        if($request->q)
        {
            $model->where(function($q) use ($request){
                $q->where('name', 'LIKE', "%{$request->q}%")
                ->orWhere('mobile', 'LIKE', "%{$request->q}%")
                ->orWhere('username', 'LIKE', "%{$request->q}%")
                ->orWhere('email', 'LIKE', "%{$request->q}%");
            });
            $current['q'] = $request->q;
        }
        return [$current, $filter];
    }
}
