<?php

namespace App\Http\Controllers\Api;



use App\Http\Requests\Api\TopicRequest;
use App\Http\Requests\Request;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TopicsController extends Controller
{
    public function index(TopicRequest $request, Topic $topic)
    {
        $topics=QueryBuilder::for(Topic::class)
        ->allowedFields(['user.id', 'user.name'])
        ->allowedIncludes('user','category')
        ->allowedFilters([
            'title',
            AllowedFilter::exact('category_id'),
            AllowedFilter::scope('withOrder')->default('recentReplied'),
        ])
        ->paginate();

        return TopicResource::collection($topics);
    }

    /**
     * [userIndex]
     */
    public function userIndex(TopicRequest $request, User $user)
    {
        $query=$user->topic()->getQuery();
        $topics=QueryBuilder::for($query)
            ->allowedIncludes('user','category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('withOrder')->default('recentReplied')
            ])
            ->paginate();
        return TopicResource::collection($topics);
    }

    public function store(TopicRequest $request,Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id=$request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }

    public function update(TopicRequest $request,Topic $topic)
    {
        $this->authorize('update',$topic);

//        $topic->user_id=$request->user()->id;
        $topic->update($request->all());
        return new TopicResource($topic);
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy',$topic);

        $topic->delete();
        return response(null, 204);
    }
}
