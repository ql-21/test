<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationsRequest;
use App\Http\Requests\Api\PhoneAuthorizationsRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Http\Requests\Api\WeappAuthorizationsRequest;
use App\Http\Requests\Request;
use App\Http\Requests\WechatRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthorizationsController extends Controller
{
    /**
     * [socialStore 第三方登录]
     * @param $type
     * @param SocialAuthorizationRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function socialStore($type,SocialAuthorizationRequest $request)
    {
        $driver = \Socialite::driver($type);
        try{
            if($code = $request->code){
                $response=$driver->getAccessTokenResponse($code);
                $token= Arr::get($response,'access_token');
            }else{
                $token = $request->access_token;
                if($type=='weixin'){
                    $driver->setOpenid($request->openid);
                }
            }

            $outherUser=$driver->userFromToken($token);

        }catch (\Exception $e) {
            throw new AuthenticationException('参数错误，获取用户信息失败');
        }



        switch ($type){
            case 'weixin':
                $unionid= $outherUser->offsetExists('uninoid')?$outherUser->offsetGet('unionid'):null;
                if($unionid){
                     $user=User::where('weixin_unionid',$unionid)->first();
                }else{
                     $user=User::where('weixin_openid',$outherUser->getId())->first();
                }

                //入库
                if(!$user){
                    $data=[
                        'name'=>$outherUser->getNickname(),
                        'avatar'=>$outherUser->getAvatar(),
                        'weixin_openid'=>$outherUser->getId(),
                        'weixin_unionid'=>$unionid,
                    ];
                    $user=User::create($data);
                }
                break;

        }

        $token=Auth::guard('api')->login($user);
        if(!$token){
            throw new AuthenticationException('获取用户信息失败');
        }

        return $this->respondWithToken($token)->setStatusCode(201);

    }

    /**
     * [store 账号密码登录]
     * @param AuthorizationsRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function store(AuthorizationsRequest $request)
    {
        foreach (['name','email','phone'] as $key=>$item){
            $data=[];
            $data[$item]=$request->username;
            $data['password']=$request->password;
            $token=Auth::guard('api')->attempt($data);
            if($token) {
                break;
            }
        }

        if(!$token){
            throw new AuthenticationException('用户名或密码错误');
        }

        return $this->respondWithToken($token)->setStatusCode(201);
    }


    public function weappStore(WeappAuthorizationsRequest $request)
    {
//        print_r($request->all());

    }

    /**
     * [qrcodeStore] 微信二维码登录
     * @param WechatRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function qrcodeStore(WechatRequest $request)
    {

        $qrcodeData=Cache::get($request->key);
        if(!$qrcodeData){
            throw new AuthenticationException('参数错误');

        }

        if($qrcodeData['status']!=20){
            throw new AuthenticationException('参数错误');
        }

        if(!$qrcodeData['oauthUser']){
            throw new AuthenticationException('参数错误');
        }
        $oauthUser=$qrcodeData['oauthUser'];

        //判断用户是否存在,入库操作
        $original=$oauthUser->getOriginal();

        $unionid= isset($original['unionid'])&&$original['unionid']?$original['unionid']:null;
        if($unionid){
            $user=User::where('weixin_unionid',$unionid)->first();
        }else{
            $user=User::where('weixin_openid',$oauthUser->getId())->first();
        }

        //入库
        if(!$user){
            $data=[
                'name'=>$oauthUser->getNickname(),
                'avatar'=>$oauthUser->getAvatar(),
                'weixin_openid'=>$oauthUser->getId(),
                'weixin_unionid'=>$unionid,
            ];
            $user=User::create($data);

        }

        Cache::forget($request->key);

        $token=Auth::guard('api')->login($user);
        if(!$token){
            throw new AuthenticationException('获取用户信息失败');
        }

        return $this->respondWithToken($token)->setStatusCode(201);

    }

    /**
     * [phoneStore] 手机号登录/注册
     * @param PhoneAuthorizationsRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function phoneStore(PhoneAuthorizationsRequest $request)
    {
        $verifyData=Cache::get($request->verification_key);

        if(!$verifyData){
            abort(403,'验证码失效');
        }

        if(!hash_equals($verifyData['code'],$request->verification_code)){
            throw new AuthenticationException('验证码错误');
        }

        $phone=$verifyData['phone'];
        //判断用户是否添加
        $user=User::where('phone',$phone)->first();

        if(!$user){
            //生成随机用户名
            $name='user'.Str::random(4).substr($phone,-4,4);
            $user = User::create([
                'name'=>$name,
                'phone'=>$phone
            ]);
        }

        //清除验证码缓存
        Cache::forget($request->verification_key);

        $token=Auth::guard('api')->login($user);
        if(!$token){
            throw new AuthenticationException('获取用户信息失败');
        }
        return $this->respondWithToken($token)->setStatusCode(201);
    }

    /**
     * [resetPassword]重置密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException\
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'verification_key'=>'required|string',
            'verification_code'=>'required|string',
            'password' => 'required|confirmed|min:8',
        ],[],[
            'verification_key'=> '验证码key',
            'verification_code'=> '验证码',
        ]);


        $verifyData=Cache::get($request->verification_key);

        if(!$verifyData){
            abort(403,'验证码失效');
        }

        if(!hash_equals($verifyData['code'],$request->verification_code)){
            throw new AuthenticationException('验证码错误');
        }

        $phone=$verifyData['phone'];
        $user=$request->user();
        if($user->phone!=$phone){
            throw new AuthenticationException('请使用绑定的手机号');
        }

        $user->password=Hash::make($request->password);
        $user->save();

        //清除验证码缓存
        Cache::forget($request->verification_key);

        return response()->json()->setStatusCode(200);

    }



    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'expires_in'=>Auth::guard('api')->factory()->getTTL()*60
        ]);
    }


    public function update()
    {
        $token = auth('api')->refresh();
        return $this->respondWithToken($token)->setStatusCode(200);

    }

    public function destroy()
    {
        auth('api')->logout();
        return response(null,204);
    }

}
