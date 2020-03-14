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
        $user = User::find($this->model->value);
        $check = Hash::check($request->password, $user->password);
        if (!$check) {
            throw ValidationException::withMessages([
                "password" => __('auth.failed')
            ]);
        }
    }

    public function passed(Request $request)
    {
        $this->model->delete();
    }

    public function register(Request $request, EnterTheory $model, array $params = [])
    {
        return EnterTheory::create([
            'key' => EnterTheory::tokenGenerator(),
            'theory' => 'password',
            'value' => $model->value,
            'parent_id' => $model->id,
            'expired_at' => Carbon::now()->addMinutes(5)
        ]);
    }

    public function rules(Request $request)
    {
        return [
            'password' => 'required|min:6'
        ];
    }
}
