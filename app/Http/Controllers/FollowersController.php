<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 用户取消关注操作
     * Author David
     * Date 2019-03-27
     */
    public function destroy(User $user)
    {
        $this->authorize('follow',$user);
        if(Auth::user()->isFollowing($user->id)){
            Auth::user()->unfollow($user->id);
        }
        return redirect()->route('users.show',$user->id);
    }

    /**
     * 用户关注操作
     * Author David
     * Date 2019-03-27
     */
    public function store(User $user)
    {
        $this->authorize('follow',$user);
        if(! Auth::user()->isFollowing($user->id)){
            Auth::user()->follow($user->id);
        }
        return redirect()->route('users.show', $user->id);
    }
}
