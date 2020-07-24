<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class VerificationCodesController extends Controller
{
    /**
     * [store] 短信验证码
     * @param VerificationCodeRequest $request
     * @param EasySms $easySms
     * @return \Illuminate\Http\JsonResponse
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     */
    public function store(VerificationCodeRequest $request,EasySms $easySms)
    {
        $phone = $request->phone;

        //校验验证码是否正确
        $captchas_key=$request->captchas_key;
        $captchas_code=$request->captchas_code;

        $captcahsData=Cache::get($captchas_key);
        if(!$captcahsData){
            abort(403,'验证码失效');

        }
        Cache::forget($captchas_key);
        if(!hash_equals($captchas_code,$captcahsData['captchas_code'])){
            abort(403,'验证码错误');

        }

        if(!app()->environment('production')){
            $code = '123456';
        }else{
            //生成验证码随机数
            $code = str_pad(random_int(1,999999),6,0,STR_PAD_LEFT);
            try{
                $result=$easySms->send($phone,[
                    'template'=>config('easysms.gateways.aliyun.templates.register'),
                    'data'=>[
                        'code'=>$code
                    ],
                ]);
            }catch (NoGatewayAvailableException $exception){
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }

        }


        $key = 'verificationCode_'.Str::random(15);
        $expiredAt = now()->addMinute(5);

        //缓存验证码5分钟过期。
        Cache::put($key,['phone'=>$phone,'code'=>$code],$expiredAt);

        return response()->json([
            'key'=>$key,
            'expired_at'=>$expiredAt->toDateTimeString(),
        ])->setStatusCode('201');

    }


}
