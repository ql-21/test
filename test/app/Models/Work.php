<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    protected $fillable=['title','type','body','cover_img','view_count'];

    //
    public function tag()
    {
        return $this->morphToMany(Tag::class,'taggables');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithOrder($query,$order){
        switch ($order){
            case 'recent':
                $query->orderBy('created_at','desc');
                break;
            case 'hot':
                $query->orderBy('view_count','desc');
                break;
            default:
                break;

        }
    }

    public function favoriteWorks()
    {
        return $this->belongsToMany(User::class,'user_favorite_works')
            ->withTimestamps();
    }

    public function likeWorks()
    {
        return $this->belongsToMany(User::class,'user_like_works')
            ->withTimestamps();
    }

    public function getIsLikeWorkAttribute()
    {
        $user=auth('api')->user();
        $work_id=$this->attributes['id'];
        if(!$user){
            return 0;
        }

        if($user->likeWorks()->find($work_id)){
            return 1;
        }

        return 0;
    }

    /**
     * [getIsFavoriteAttribute]用户是否收藏
     * @return int
     */
    public function getIsFavoriteAttribute()
    {
        $user=auth('api')->user();
        $work_id=$this->attributes['id'];
        if(!$user){
            return 0;
        }

        if($user->favoriteWorks()->find($work_id)){
            return 1;
        }

        return 0;
    }

}
