<?php
namespace App\EnterTheory;

use Illuminate\Http\Request;
use Str;
use Carbon\Carbon;
use App\EnterTheory;
use Illuminate\Validation\ValidationException;

class MobileCode extends Theory
{
    public function boot(Request $request)
    {
        return $this->pass($request);
    }
    public function passed(Request $request)
    {
        $this->model->delete();
    }
    public function register(Request $request, EnterTheory $model = null, array $parameters = [])
    {
        $find = EnterTheory::where([
            'parent_id' => $model->id,
            ['expired_at', '>', Carbon::now()],
            'theory' => 'mobileCode'
        ])->first();
        return $find ?: EnterTheory::create([
            'key' => EnterTheory::tokenGenerator(),
            'parent_id' => $model->id,
            'value' =>  config('app.debug') ? 130171 : rand(130171, 999999),
            'theory' => 'mobileCode',
            'expired_at' => Carbon::now()->addMinutes(5)
        ]);
    }

    public function rules(Request $request)
    {
        $model = $this->model;
        return [
            'code' => ['required', function ($key, $value, $fail) use ($model) {
                if ($model->value != $value) {
                    $fail('mobileCode.failed');
                }
            }]
        ];
    }
}