<?php
namespace App\EnterTheory;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\EnterTheory;
use App\User;
use App\Token;
use Illuminate\Support\Facades\Hash;

class ForgetPassword extends Theory
{
    public function boot(Request $request)
    {
        return $this->pass($request);
    }
    public function passed(Request $request)
    {
        $user = User::find($this->model->user_id);
        $user->update(['password'=> Hash::make($request->password)]);
        Token::where('user_id', $user->id)->update(['revoked' => 1]);
        $this->model->delete();
        return $this->model->parent->theory->run($request);
    }
    public function register(Request $request, EnterTheory $model, array $parameters = [])
    {
        $theory = EnterTheory::where('parent_id', $model->id)
        ->where('theory', 'forgetPassword')
        ->where('expired_at', '>', Carbon::now())
        ->first();
        if(!$theory)
        {
            $theory = EnterTheory::create([
                'parent_id' => $model->id,
                'user_id' => $model->user_id,
                'key' => EnterTheory::tokenGenerator(),
                'theory' => 'forgetPassword',
                'trigger' => 'mobileCode',
                'expired_at' => Carbon::now()->addMinutes(5)
            ]);
        }
        if ($theory->getAttribute('trigger')) {
            return $theory->trigger->register($request, $theory);
        }
        return $theory;
    }

    public function rules(Request $request)
    {
        return [
            'password' => 'required|min:6'
        ];
    }
}
