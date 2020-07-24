<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * 用户主页
 * Class ProfileController
 * @package App\Http\Controllers\Api
 */
class ProfileController extends Controller
{
    /**
     * [index] 用户主页基本信息
     * @param User $user
     * @return UserResource
     */
    public function index(User $user){

        //判断是不是自己
        $is_me=auth('api')->id()==$user->id;

        //基本信息
        $user['is_me']=$is_me;

        //收获喜欢、收藏、浏览数，发布等数量
        $user['like_count']=$user->work()->withCount('likeWorks')->get()->sum('like_works_count');
        $user['favorite_count']=$user->work()->withCount('favoriteWorks')->get()->sum('favorite_works_count');
        $user['view_count']=$user->work()->get()->sum('view_count');
        $user['work_count']=$user->work()->where('status',1)->count();

        $userResource=new UserResource($user);
        if(!$is_me){
            return $userResource;
        }

        return $userResource->showSensitiveFields();



    }
}
