<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Request;
use App\Http\Resources\NotificationResource;

class NotificationsController extends Controller
{
    /**
     * [index] 消息通知列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $user=$request->user();
        $notifications=$user->notifications()->paginate();
        $user->markAsRead();
        return NotificationResource::collection($notifications);
    }

    /**
     * [stats] 消息通知统计
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $user=$request->user();
        $unread_count=$user->notification_count;
        return response()->json([
            'unread_count' => $unread_count
        ]);
    }



}
