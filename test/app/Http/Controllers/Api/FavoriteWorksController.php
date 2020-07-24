<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\FavoriteWorksRequest;
use App\Http\Resources\FavoriteWorksResource;
use App\Http\Resources\WorkResource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FavoriteWorksController extends Controller
{

    /**
     * [myIndex] 我的收藏
     * @param FavoriteWorksRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function myIndex(FavoriteWorksRequest $request)
    {
        $user = $request->user();
        $works= QueryBuilder::for($user->favoriteWorks()->withCount(['favoriteWorks', 'likeWorks'])->getQuery())
            ->allowedIncludes('tag','user')
            ->allowedAppends(['is_favorite','is_like_work'])
            ->paginate();
        return WorkResource::collection($works);
    }

    /**
     * [store] 用户添加收藏
     * @param FavoriteWorksRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(FavoriteWorksRequest $request)
    {
        $user=$request->user();

        if($user->favoriteWorks()->find($request->work_id)){
            return $this->errorResponse(403, '请勿重复收藏', 1003);
        }

        $user->favoriteWorks()->attach($request->work_id);

        return response(null, 200);
    }

    /**
     * [destroy] 取消收藏
     * @param FavoriteWorksRequest $request
     * @param $work_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(FavoriteWorksRequest $request,$work_id)
    {
        $user=$request->user();

        $user->favoriteWorks()->detach($work_id);

        return response(null, 200);
    }
}
