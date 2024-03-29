<?php
namespace App\EnterTheory;

use Illuminate\Http\Request;
use Str;
use Carbon\Carbon;
use App\EnterTheory;
use App\User;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Maravel\Lib\MobileRV;

class MobileCode extends Theory
{
    public function boot(Request $request)
    {
        return $this->pass($request);
    }
    public function passed(Request $request)
    {
        if($this->model->type == 'verify')
        {
            $this->model->user->status = 'active';
            $this->model->user->save();
        }
        $this->model->delete();
    }
    public function register(Request $request, EnterTheory $model = null, array $parameters = [])
    {
        if($request->authorized_key && !isset($parameters['mobile']) && MobileRV::parse($request->authorized_key)){
            list($mobile, $c, $code) = MobileRV::parse($request->authorized_key);
            $parameters['mobile'] = $code.$mobile;
        }
        $find = EnterTheory::where([
            'parent_id' => $model->id,
            ['expired_at', '>', Carbon::now()],
            'theory' => 'mobileCode',
            'user_id' => isset($parameters['verify_id']) ? $parameters['verify_id'] : null,
            'type' => isset($parameters['verify_id']) ? 'verify' : null
        ])->first();
        if($find){
            return $find;
        }
        $muted = config('app.debug') || config('app.env') == 'local';
        if($muted){
            $value =  130171;
        }else{
            $value =  rand(130171, 999999);
        }
        $theory = EnterTheory::create([
            'key' => EnterTheory::tokenGenerator(),
            'parent_id' => $model->id,
            'value' =>  $value,
            'theory' => 'mobileCode',
            'meta' => ['authorized_key' => $request->mobile ?: $request->authorized_key],
            'expired_at' => isset($parameters['expires_at']) ? $parameters['expires_at'] : Carbon::now()->addMinutes(5),
            'user_id' => isset($parameters['verify_id']) ? $parameters['verify_id'] : null,
            'type' => isset($parameters['verify_id']) ? 'verify' : 'temp'
        ]);
        $user = $this->model->user ?: new User;
        try{
            $user->mobileCodeTheory($request, $parameters, $model, $theory, $value);
        }catch(Exception $e){
            Log::info(json_encode([
                'request' => $request->all(),
                'parameters' => $parameters,
            ]));
            throw $e;
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
        $model = $this->model;
        return [
            'code' => ['required','numeric', function ($key, $value, $fail) use ($model) {
                if ($model->value != $value) {
                    $fail('mobileCode.failed');
                }
            }]
        ];
    }
}
