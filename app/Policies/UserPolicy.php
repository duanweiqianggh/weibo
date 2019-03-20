<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //2019-03-20 确认前台传来的用户ID是真实的
    public function update(User $currentUser,User $user)
    {
        return $currentUser->id === $user->id;
    }

    //2019-03-21 01:14 检查权限是否为管理员
    public function destroy(User $currentUser,User $user)
    {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
