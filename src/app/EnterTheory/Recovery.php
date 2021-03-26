<?php
namespace App\EnterTheory;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\EnterTheory;
use App\User;
use App\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Recovery extends Theory
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
        ->where('theory', 'recovery')
        ->where('expired_at', '>', Carbon::now())
        ->first();
        $muted = config('app.debug') || config('app.env') == 'local';
        if($muted && $theory){
            EnterTheory::where('parent_id', $theory->id)->delete();
            $theory->delete();
        }
        elseif($theory)
        {
            throw ValidationException::withMessages([
                $request->original_method => __('Try after :seconds seconds', ['seconds' => Carbon::now()->diffInSeconds($theory->expired_at)])
            ]);
        }
            $theory = EnterTheory::create([
                'parent_id' => $model->id,
                'user_id' => $model->user_id,
                'key' => EnterTheory::tokenGenerator(),
                'theory' => 'recovery',
                'trigger' => 'mobileCode',
                'type' => 'temp',
                'meta' => ['authorized_key' => $model->user->mobile],
                'expired_at' => Carbon::now()->addMinutes(3)
            ]);
        if ($theory->getAttribute('trigger')) {
            return $theory->trigger->register($request, $theory);
        }
        return $theory;
    }

    public function response()
    {
        $result = parent::response();
        $result['authorized_key'] = $this->result->meta['authorized_key'];
        return $result;
    }

    public function rules(Request $request)
    {
        return [
            'password' => 'required|min:6'
        ];
    }
}
