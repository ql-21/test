<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Category;
use App\Models\User;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        $faker=app(\Faker\Generator::class);

        //获取分类
        $categroys_id=Category::all()->pluck('id')->toArray();
        //获取用户
        $users_id=User::all()->pluck('id')->toArray();


        $topics = factory(Topic::class)->times(100)->make()->each(function ($topic, $index) use ($faker,$users_id,$categroys_id)  {
            $topic->user_id=$faker->randomElement($users_id);
            $topic->category_id=$faker->randomElement($categroys_id);
        });

        Topic::insert($topics->toArray());
    }

}

