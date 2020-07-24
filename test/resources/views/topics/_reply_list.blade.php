<div  class="">
    回复数：{{$topic->reply_count}}
</div>
<br>
<ul class="list-unstyled">
    @foreach($replies as $reply)
        <li class=" media" name="reply{{$reply->id}}" id="reply{{$reply->id}}">
            <div class="media-left">
                <a href="{{route('users.show',[$reply->user_id])}}">
                    <img src="{{$reply->user->avatar}}" class="media-object img-thumbnail mr-3" style="width: 48px; height: 48px">
                </a>
            </div>
            <div class="media-body">
                <div class="media-heading mt-0 mb-1 text-secondary">
                    <a href="{{route('users.show',$reply->user_id)}}" title="{{$reply->user->nickname}}">
                        {{$reply->user->nickname}}
                    </a>
                    <span class="text-secondary"> • </span>
                    <span class="meta text-secondary">{{$reply->created_at->diffForHumans()}}</span>
                    {{-- 回复删除按钮 --}}
                    @if(replied_author($reply))
                    <span class="meta float-right ">
                        <form method="POST" action="{{route('replies.destroy',[$reply->id])}}" onsubmit="return confirm('确定删除评论吗？')">
                            {{csrf_field()}}
                            {{method_field('DELETE')}}
                            <button type="submit" class="btn btn-default btn-xs pull-left text-secondary">
                            <i class="far fa-trash-alt"></i>
                          </button>
                        </form>
                    </span>
                    @endif
                </div>
                <div class="reply-content text-secondary">
                    {!!  $reply->content !!}
                </div>
            </div>
        </li>
        @if ( ! $loop->last)
            <hr>
        @endif
    @endforeach
</ul>