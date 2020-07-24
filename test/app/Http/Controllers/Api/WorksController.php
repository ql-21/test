<?php

namespace App\Http\Controllers\Api;

use App\Events\AddRead;
use App\Http\Requests\Api\WorkRequest;
use App\Http\Resources\WorkResource;
use App\Models\User;
use App\Models\Work;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WorksController extends Controller
{
    /**
     * [index] 作品列表
     * @param WorkRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(WorkRequest $request) {
        $works = QueryBuilder::for(Work::withCount(['favoriteWorks', 'likeWorks']))
            ->allowedFields(['user.id', 'user.name', 'user.avatar'])
            ->allowedIncludes('tag', 'user')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('tag.id'),
                AllowedFilter::exact('type'),
//                AllowedFilter::exact('status')->default([0,1]),
                AllowedFilter::scope('withOrder')->default('hot'),
            ])
            ->allowedAppends(['is_favorite', 'is_like_work'])
            ->where('status', 1)
            ->paginate();
        return WorkResource::collection($works);
    }

    /**
     * [userIndex] 用户作品
     * @param WorkRequest $request
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function userIndex(WorkRequest $request, User $user)
    {
        $works=QueryBuilder::for($user->work()->withCount(['favoriteWorks', 'likeWorks'])->getQuery())
            ->allowedFields(['user.id', 'user.name', 'user.avatar'])
            ->allowedIncludes('tag', 'user')
            ->allowedFilters([
                AllowedFilter::exact('tag.id'),
                AllowedFilter::scope('withOrder')->default('hot'),
            ])
            ->where('status', 1)
            ->paginate();

        return WorkResource::collection($works);
    }


    /**
     * [show] 详情
     * @param $project_id
     * @return ProjectResource
     */
    public function show(WorkRequest $request,$work_id)
    {
        $work=QueryBuilder::for(Work::withCount(['favoriteWorks','likeWorks']))
            ->allowedFields(['user.id', 'user.name'])
            ->allowedIncludes('tag','user')
            ->allowedAppends(['is_favorite','is_like_work'])
            ->where('status',1)
            ->findOrFail($work_id);
        //增加浏览记录
        event(new AddRead($work));

        return new WorkResource($work);

    }

    /**
     * [store] 创建作品
     * @param ProjectRequest $request
     */
    public function store(WorkRequest $request,Work $work)
    {

        $user = $request->user();
        $data = $request->only(['title', 'type', 'body']);

        //预留缩略图
        $work->fill($data);
        $work->user_id=$user->id;
        $work->save();

        return new WorkResource($work);

    }

    /**
     * [edit] 编辑作品（获取信息）
     * @param Project $project
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Work $work)
    {
        $this->authorize('update',$work);

        return new WorkResource($work);
    }


    public function update()
    {

    }

    /**
     * [release] 发布作品
     * @param ProjectRequest $request
     * @param Project $project
     * @return ProjectResource
     */
    public function release(WorkRequest $request, Work $work)
    {
        $this->authorize('update',$work);

        $user = $request->user();
        $data = $request->only(['title', 'type', 'body']);

        $work->fill($data);
        $work->user_id=$user->id;
        $work->status=1;
        $work->update();

        //标签存储
        if($tags = $request->tags){
            $work->tag()->sync($tags);
        }

        return new WorkResource($work);
    }

    /**
     * [destroy] 删除作品
     * @param WorkRequest $request
     * @param Work $work
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(WorkRequest $request, Work $work)
    {
        $this->authorize('destroy',$work);

        $work->status=2;
        $work->update();

        return response(null, 204);
    }
}
