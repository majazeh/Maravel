<?php
namespace App\EnterTheory;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\EnterTheory;
use App\User;

class Register extends Theory
{
    public function passed(Request $request)
    {
        $params = $this->model->meta;
        $params['status'] = 'active';
        if($user = User::where('mobile', $this->model->meta['mobile'])->first())
        {
            $user->update($params);
        }
        else
        {
            $user = User::create($params);
        }
        $this->model->delete();
        $auth = EnterTheory::create([
            'key' => $user->mobile,
            'value' => $user->id,
            'theory' => 'auth',
            'trigger' => config('auth.trigger', 'password'),
        ]);
        return $auth->run($request);
    }
    public function register(Request $request, EnterTheory $model, array $parameters = [])
    {
        $parameters['status'] = User::count() ? User::defaultStatus() : 'active';
        $parameters['type'] = User::count() ? User::defaultType() : 'admin';
        if (!User::where('mobile', $parameters['mobile'])->first()) {
            User::create($parameters);
        }
        if($theory = EnterTheory::where('theory', 'register')->where('key', $parameters['mobile'])->first())
        {
            $theory->update([
                'trigger' => config('auth.autoActive', false) ? null : config('auth.activeMethod', 'mobileCode'),
                'meta' => $parameters
            ]);
        }
        else
        {
            $theory = EnterTheory::create([
                'meta' => $parameters,
                'key' => $parameters['mobile'],
                'theory' => 'register',
                'trigger' => config('auth.autoActive', false) ? null : config('auth.activeMethod', 'mobileCode'),
                'expired_at' => Carbon::now()->addMinutes(5)
            ]);
        }
        if($theory->getAttribute('trigger'))
        {
            return $theory->trigger->register($request, $theory);
        }
        return $theory;
    }

    public function rules(Request $request)
    {
        return [];
    }
}
