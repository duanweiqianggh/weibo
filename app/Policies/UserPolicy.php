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

    /**
     * 确认前台传来的用户ID是真实的
     * Author David
     * Date 2019-03-25 01:14
     * @var bool
     */
    public function update(User $currentUser,User $user)
    {
        return $currentUser->id === $user->id;
    }

    /**
     * 检查权限是否为管理员
     * Author David
     * Date 2019-03-25 01:14
     * @var bool
     */
    public function destroy(User $currentUser,User $user)
    {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
