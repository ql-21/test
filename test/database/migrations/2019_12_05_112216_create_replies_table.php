<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration 
{
	public function up()
	{
		Schema::create('replies', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('topic_id')->index()->unsigned()->default(0);
            $table->bigInteger('user_id')->unsigned()->index()->default(0);
            $table->text('content');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('replies');
	}
}
