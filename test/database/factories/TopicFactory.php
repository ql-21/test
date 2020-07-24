<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {

    //修改时间，随机生成一个月时间
    $updated_at=$faker->dateTimeThisMonth();
    //创建时间，不超过修改时间
    $created_at=$faker->dateTimeThisMonth($updated_at);

    return [
        'title'=>$faker->sentence(),
        'body'=>$faker->text(),
        'excerpt'=>$faker->title,
        'updated_at'=>$updated_at,
        'created_at'=>$created_at,
    ];
});
