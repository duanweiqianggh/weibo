<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //查询所有用户
        $users = User::all();
        //取出第一条用户信息作为测试账号
        $user = $users->first();
        $user_id = $user->id;

        //获取其他所有用户的ID集合
        $followers = $users->slice($user_id);
        $follower_ids = $followers->pluck('id')->toArray();

        //关注除了1号用户(也就是测试账号本身)以外的所有用户
        $user->follow($follower_ids);

        //除了一号用户本身 所有其他账号都关注1号账号
        foreach ($followers as $follower){
            $follower->follow($user_id);
        }
    }
}
