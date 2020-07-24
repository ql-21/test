<li class="media">
    <div class="media-left">
        <a href="{{route('users.show',$notification->data['user_id'])}}">
            <img class="media-object img-thumbnail mr-3 " alt="{{$notification->data['user_name']}}" src="{{$notification->data['user_avatar']}}" style="width: 48px;height:48px">
        </a>
    </div>
    <div class="media-body">
        <div class="media-heading mt-0 mb-1 text-secondary">
            <a href="{{route('users.show',[$notification->data['user_id']])}}">{{$notification->data['user_name']}}</a>
            喜欢了你的文章
            <a href="">《{{ $notification->data['work_title'] }}》</a>

            <span class="meta float-right" title="{{$notification->created_at}}">
                <i class="far fa-clock"></i>
                {{$notification->created_at->diffForHumans()}}
            </span>
        </div>
        <div class="reply-content">

        </div>
    </div>
</li>