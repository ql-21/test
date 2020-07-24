<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    protected $hidden = [];

    public $timestamps=false;

    public function work()
    {
        return $this->morphedByMany(Work::class,'taggables');
    }
}
