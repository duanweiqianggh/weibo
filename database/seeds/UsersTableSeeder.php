<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //创建随机用户
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password','remember_token'])->toArray());

        //修改一个自己账号的虚拟数据
        $user = User::find(1);
        $user->name = 'David';
        $user->email = '1306857779@qq.com';
        $user->password =  bcrypt('123456');
        $user->is_admin = true;
        $user->save();
    }
}
