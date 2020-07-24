<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;

$factory->define(App\Models\Reply::class, function (Faker $faker) {

    return [
        'content'=>$faker->text(),
        'created_at'=>$faker->dateTimeThisMonth(),
        'updated_at'=>$faker->dateTimeThisMonth(),
    ];
});
