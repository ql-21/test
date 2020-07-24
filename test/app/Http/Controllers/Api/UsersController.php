<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UsersRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    public function store(UsersRequest $request)
    {
        $verifyData=Cache::get($request->verification_key);

        if(!$verifyData){
            abort(403,'验证码失效');
        }

        if(!hash_equals($verifyData['code'],$request->verification_code)){
            throw new AuthenticationException('验证码错误');
        }

        $user = User::create([
            'name'=>$request->name,
            'phone'=>$verifyData['phone'],
            'password'=>bcrypt($request->password),
        ]);

        //清除验证码缓存
        Cache::forget($request->verification_key);
        return new UserResource($user);

    }


    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function me(Request $request)
    {
        return (new UserResource($request->user()))->showSensitiveFields();
    }

    public function update(UsersRequest $request)
    {
        $user = $request->user();
        $data = $request->only(['name', 'email', 'introduction','nickname','sex']);


        if($request->avatar_image_id){
            $image=Image::find($request->avatar_image_id);

            $data['avatar']=$image?$image->path:$user->image;
        }

        $user->update($data);

        return (new UserResource($user))->showSensitiveFields();
    }
}
