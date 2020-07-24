<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class Reply extends Model
{
    use Notifiable;

    protected $fillable = [ 'content','topic_id'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query) {
        return $query->orderBy('created_at','desc');
    }


}
