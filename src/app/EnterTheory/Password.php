<?php
namespace App\EnterTheory;
use Illuminate\Http\Request;
use App\User;
use App\EnterTheory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Password extends Theory
{
    protected function boot(Request $request)
    {
        if($this->model->trigger)
        {
            return $this->trigger($request);
        }
        $user = User::find($this->model->parent->user_id);
        $check = Hash::check($request->password, $user->password);
        if (!$check) {
            throw ValidationException::withMessages([
                "password" => __('auth.failed')
            ]);
        }
        return $this->pass($request);
    }

    public function passed(Request $request)
    {
        $this->model->delete();
    }

    public function register(Request $request, EnterTheory $model, array $params = [])
    {
        $find = EnterTheory::where([
            'parent_id' => $model->id,
            ['expired_at', '>', Carbon::now()],
            'theory' => 'password',
        ])->first();        
        return $find ?: EnterTheory::create([
            'key' => EnterTheory::tokenGenerator(),
            'theory' => 'password',
            'value' => $model->value,
            'parent_id' => $model->id,
            'type' => 'temp',
            'expired_at' => Carbon::now()->addMinutes(5),
            'meta' => ['authorized_key' => $request->authorized_key]
        ]);
    }

    public function response()
    {
        $response = parent::response();
        $response['authorized_key'] = $this->result->meta['authorized_key'];
        return $response;
    }

    public function rules(Request $request)
    {
        return [
            'password' => 'required|min:6'
        ];
    }
}
