<?php

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=app(Faker\Generator::class);

        $tags=factory(Tag::class)->times(10)->make()->each(function($tag,$index) use ($faker){

        });
        Tag::insert($tags->toArray());
    }
}
