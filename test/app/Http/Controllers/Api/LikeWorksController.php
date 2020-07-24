<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\LikeWorksRequest;
use App\Http\Resources\LikeWorksResource;
use App\Http\Resources\WorkResource;
use App\Models\User;
use App\Notifications\LikeWorked;
use Spatie\QueryBuilder\QueryBuilder;

class LikeWorksController extends Controller
{
    /**
     * [userIndex] 用户喜欢列表
     * @param LikeWorksRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function userIndex(LikeWorksRequest $request,User $user)
    {
        $works=QueryBuilder::for($user->likeWorks()->withCount(['likeWorks','favoriteWorks'])->getQuery())
            ->allowedIncludes('tag','user')
            ->allowedAppends(['is_favorite','is_like_work'])
            ->paginate();
        return WorkResource::collection($works);

    }

    /**
     * [store] 添加喜欢
     * @param LikeWorksRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     */
    public function store(LikeWorksRequest $request)
    {
        //判断是否已喜欢
        $user=$request->user();
        $work_id=$request->work_id;
        if($user->likeWorks()->find($work_id)){
            return $this->errorResponse(403,'请勿重复喜欢','1003');
        }

        $user->likeWorks()->attach($work_id);

        $work=$user->likeWorks()->find($work_id);

        $notifications=$work->user->notifications;
        foreach($notifications as $notification){
            if($notification->data['work_id']==$work_id){
                return response(null, 200);
            }
        }

        $work->user->notifyLikeWork(new LikeWorked($work,$user));

        return response(null, 200);
    }

    /**
     * [destroy] 用户取消喜欢
     * @param LikeWorksRequest $request
     * @param $work_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(LikeWorksRequest $request,$work_id)
    {
        $user=$request->user();
        $user->likeWorks()->detach($work_id);
        return response(null, 200);
    }
}
