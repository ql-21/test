<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchasRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CaptchasController extends Controller
{
    /**
     * [store] 图形验证码
     * @param CaptchasRequest $request
     * @param CaptchaBuilder $captchaBuilder
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CaptchasRequest $request,CaptchaBuilder $captchaBuilder)
    {
        $phone = $request->phone;

        //key名
        $key = 'Captchas_'.Str::random(15);
        $captcha=$captchaBuilder->build();
        $expiredAt = now()->addMinute(5);

        Cache::put($key,[
           'phone'=>$phone,
           'captchas_code'=>$captcha->getPhrase(),
        ],$expiredAt);

        return response()->json([
            'key'=>$key,
            'expired_at'=>$expiredAt->toDateTimeString(),
            'captchas_content'=>$captcha->inline(),
        ])->setStatusCode('201');

    }

    /**
     * [loginQr]登录二维码
     */
    public function loginQr()
    {
        //创建key
        $key='qrcode_'.md5(Str::random(32));
        //创建ticket
        $ticket=Str::random(32);
        //拼接url
        $linkurl=config('app.url').'/wechat/qrOauth/'.$key;
        //生成二维码
        $filepath=public_path('uploads/'.$ticket.'.png');
        QrCode::format('png')->size(250)->margin(1)->generate($linkurl,$filepath);
        $filedata=file_get_contents($filepath);
        $qrImageBase64='data:image/png;base64,' .base64_encode($filedata);
        //删除图片
        Storage::disk('admin')->delete('qrcode.png');

        $expiredAt = now()->addMinute(5);
        Cache::put($key,[
            'ticket'=>$ticket,
            'oauthUser'=>'',
            'status'=>10
        ],$expiredAt);

        return response()->json([
            'key'=>$key,
            'expired_at'=>$expiredAt->toDateTimeString(),
            'qrcode'=>$qrImageBase64,
            'linkurl'=>$linkurl
        ])->setStatusCode('201');

    }
}
