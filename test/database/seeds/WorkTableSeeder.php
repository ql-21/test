<?php

use App\Models\Tag;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Seeder;

class WorkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=app(Faker\Generator::class);

        $user_ids=User::all()->pluck('id')->toArray();
        $tag_ids=Tag::all()->pluck('id')->toArray();

        $works=factory(Work::class)->times(20)->create()->each(function($work,$index) use ($faker,$user_ids,$tag_ids){
            $work->tag()->attach($faker->randomElements($tag_ids,2));
        });

//        Work::insert($works->toArray());
    }
}
