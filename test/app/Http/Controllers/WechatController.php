<?php

namespace App\Http\Controllers;

use App\Http\Requests\WechatRequest;
use App\Models\User;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Cache;

class WechatController extends Controller
{
    /**
     * [oauth]微信二维码授权获取用户信息
     * @param WechatRequest $request
     */
    public function qrOauth(WechatRequest $request)
    {

        $qrcodeData=Cache::get($request->key);
        if(!$qrcodeData){
            return response('登录二维码失效', 200);
        }
        if(!$qrcodeData['ticket']){
            return response('缺少参数!', 200);
        }

        if($qrcodeData['status']==20){
            return response('登录成功', 200);
        }
        $oauthUser = session('wechat.oauth_user.default'); // 拿到授权用户资料

        $expiredAt = now()->addMinute(5);
        Cache::put($request->key,[
            'ticket'=>$qrcodeData['ticket'],
            'oauthUser'=>$oauthUser,
            'status'=>20,
        ],$expiredAt);

        return response('登录成功!', 200);

    }
}
