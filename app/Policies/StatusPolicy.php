<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Status;

class StatusPolicy
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
     * 删除微博权限检查
     * Author David
     * Date 2019-03-25 18:20
     * @var bool
     */
    public function destroy(User $user, Status $status)
    {
        return $user->id === $status->user_id;
    }
}
