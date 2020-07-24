@extends('layouts.app')

@section('title',$topic->title)
@section('description',$topic->description)
@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-3 hidden-sm hidden-xs author-info">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        作者：{{$topic->user->nickname}}
                    </div>
                    <hr>
                    <div class="media">
                        <div align="center">
                            <a href="{{route('users.show',$topic->user_id)}}">
                                <img class="thumbnail img-fluid" src="{{$topic->user->avatar}}" width="300px" height="300px">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 topic-content">
            <div class="card">
                <div class="card-body">
                    <h1 class="text-center mt-3 mb-3">
                        {{$topic->title}}
                    </h1>

                    <div class="article-meta text-center text-secondary">
                        {{$topic->created_at->diffForHumans()}}
                        <label class="">阅读量:{{$topic->view_count}}</label>

                    </div>

                    <div class="topic-body mt-4 mb-4">
                        {!! $topic->body !!}
                    </div>

                    <div class="operate">
                        <hr>
                        @can('update',$topic)
                        <a href="{{route('topics.edit',$topic->id)}}">
                            <i class="far fa-edit"></i>
                            编辑
                        </a>
                        <form style="display: inline-block;" action="{{route('topics.destroy',$topic->id)}}" method="POST" onsubmit="return confirm('您确定删除吗？');">
                            {{csrf_field()}}
                            {{method_field('DELETE')}}
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="far fa-trash-alt"></i>
                                删除
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card topic-reply mt-4">
                <div class="card-body">
                    @includeWhen(Auth::check(),'topics._reply_box',['topic'=>$topic])
                    @include('topics._reply_list',['replies'=>$topic->reply()->with('user')->recent()->get(),'topic'=>$topic])
                </div>

            </div>
            <div class="card reply_content">
            </div>
        </div>
    </div>

@endsection
