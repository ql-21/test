<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Work;
use Faker\Generator as Faker;

$factory->define(Work::class, function (Faker $faker) {
    $user_ids=User::all()->pluck('id')->toArray();
    return [
        'title'=>$faker->sentence(),
        'body'=>$faker->text(),
        'status'=>1,
        'created_at'=>$faker->dateTime(),
        'updated_at'=>$faker->dateTime(),
        'user_id'=>$faker->randomElement($user_ids)
    ];
});
