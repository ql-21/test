<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //获取faker实例
        $faker=app(\Faker\Generator::class);
        //头像假数据
        $avatars=[
            "http://www.lar_demo2.me/uploads/avatar/201911/28/1574927214_69wInlFEL3.jpeg",
            "http://www.lar_demo2.me/uploads/avatar/201911/21/1574328137_Y6Ttt1MimS.jpeg",
            "http://www.lar_demo2.me/uploads/avatar/201911/21/1574315354_TvQ68TZM8T.jpeg",
            "http://www.lar_demo2.me/uploads/avatar/201911/28/1574927368_jLI6ASbxpJ.jpg",
            "http://www.lar_demo2.me/uploads/avatar/201911/29/1574992630_AORMCE4ErE.jpeg",
            "http://www.lar_demo2.me/uploads/avatar/201911/29/1574992683_CZWK3X0K9u.jpeg",
            "http://www.lar_demo2.me/uploads/avatar/201911/29/1574992720_ccOloKLQ1F.jpeg",
            "http://www.lar_demo2.me/uploads/avatar/201911/29/1574992762_AAHyQZkIAf.jpg",
        ];

        //生成数据集合
        $users=factory(\App\Models\User::class)->times(12)->make()->each(function ($user,$index) use ($faker,$avatars){
            $user->avatar=$faker->randomElement($avatars);
        });
        //让隐藏字段可见，并对数据集合转换为数组
        $user_array=$users->makeVisible(['password','remember_token'])->toArray();
        //插入到数据库中
        User::insert($user_array);
        //单独处理第一个用户的数据
        $first_user=User::find(1);
//        $first_user->assignRole(['Founder']);
        $first_user->name = 'mengxiao';
        $first_user->nickname="梦晓";
        $first_user->email= '948585377@qq.com';
        $first_user->avatar="http://www.lar_demo2.me/uploads/avatar/201911/29/1574994131_QaQcsiyM0W.jpeg";
        $first_user->save();


        $two_user=User::find(2);
        $two_user->assignRole(['Admin']);
        $two_user->name = 'admin';
        $two_user->email= 'mengxiao@wanshantechnology.com';
        $two_user->avatar="http://www.lar_demo2.me/uploads/avatar/201911/29/1574992630_AORMCE4ErE.jpeg";
        $two_user->save();
    }
}
