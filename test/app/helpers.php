<?php

if(!function_exists('route_class')){
    /**
     * [route_class]获取路由class名
     * @return mixed
     */
    function route_class(){
        return str_replace('.','-',\Illuminate\Support\Facades\Route::currentRouteName());
    }
}

if(!function_exists('category_actove')){
    /**
     * [category_active] 分类active选中函数
     * @param $category_id
     * @return string
     */
    function category_active($category_id){
        return active_class(if_route('categories.show')&&if_route_param('category',$category_id));
    }
}

if(!function_exists('make_excerpt')){
    function make_excerpt($value,$length=200){
        $excerpt=trim(preg_replace('/\r\n|\r|\n+/',' ',strip_tags($value)));
        return \Illuminate\Support\Str::limit($excerpt,$length);
    }
}

if(!function_exists('route_topics_slug')){
    /**
     * [route_topics_slug]获取topics_slug函数
     * @param $topic
     * @return string
     */
    function route_topics_slug($topic){
        return route('topics.show',[$topic->id,$topic->slug]);
    }
}

function replied_author($reply){
    //判断是否是作者
    if(Auth::id()==$reply->topic->user_id || Auth::id()==$reply->user_id){
        return true;
    }
}

if (!function_exists('manage_contents')) {
    function manage_contents()
    {
        return Auth::check() && Auth::user()->can('manage_contents');
    }
}





