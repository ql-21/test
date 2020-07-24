<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmailContract,JWTSubject
{
    use MustVerifyEmailTrait;
//    use Notifiable {
//        notify as protected laravelNotify;
//    }
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar','nickname','sex',
        'phone','weixin_openid','weixin_unionid','weapp_openid','weixin_session_key'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','weixin_openid','weixin_unionid','weapp_openid','weixin_session_key'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function topic()
    {
        return $this->hasMany(Topic::class);
    }

    public function reply()
    {
        return $this->hasMany(Reply::class);
    }

    public function work()
    {
        return $this->hasMany(Work::class);
    }

    public function favoriteWorks()
    {
        return $this->belongsToMany(Work::class,'user_favorite_works')
            ->orderBy('user_favorite_works.created_at','desc')
            ->withTimestamps();
    }

    public function likeWorks()
    {
        return $this->belongsToMany(Work::class,'user_like_works')
            ->orderBy('user_like_works.created_at','desc')
            ->withTimestamps();
    }

    /**
     * [notify] 话题回复通知消息
     * @param $instance 重构notify
     */
    public function notifyReply($instance)
    {
        //如果评论自己，不需要发送评论
        if(Auth::id()==$this->id){
            return;
        }

        if(method_exists($instance,'toDatabase')){
            $this->increment('notification_count');
        }


//        $this->laravelNotify($instance);
        $this->notify($instance);
    }

    /**
     * [notifyLikeWork] 喜欢作品通知消息
     * @param $instance
     */
    public function notifyLikeWork($instance)
    {
        if(Auth::id()==$this->id){
            return;
        }

        if(method_exists($instance,'toDatabase')){
            $this->increment('notification_count');
        }


        $this->notify($instance);

    }

    public function markAsRead()
    {
        $this->notification_count=0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
