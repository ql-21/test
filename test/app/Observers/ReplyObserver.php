<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content,'user_topic_body');
    }

    public function created(Reply $reply)
    {
        $topic= $reply->topic;
        $topic->reply_count=$topic->reply->count();
        $topic->save();

        //发送通知
        $reply->topic->user->notifyReply(new TopicReplied($reply));
    }

    public function updating(Reply $reply)
    {
        //
    }

    public function deleted(Reply $reply)
    {
        $topic= $reply->topic;
        $topic->reply_count=$topic->reply->count();
        $topic->save();
    }
}