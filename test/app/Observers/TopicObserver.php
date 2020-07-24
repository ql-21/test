<?php

namespace App\Observers;

use App\Jobs\TranslateSlug;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic)
    {
        //$translate=new TranslateHandles();
	    $topic->body = clean($topic->body,'user_topic_body');
	    $topic->excerpt=make_excerpt($topic->body);


    }

    public function saved(Topic $topic)
    {
        if(!$topic->slug){
            TranslateSlug::dispatch($topic);
        }
    }

    public function deleted(Topic $topic)
    {
        DB::table('replies')->where(['topic_id'=>$topic->id])->delete();
//        $topic->reply()->delete();
    }
}