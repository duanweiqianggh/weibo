<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    /**
     * 注册新用户前监听事件 生成激活码
     * Author David
     * Date 2019-03-21
     * @var array
     */
    static function boot()
    {
        parent::boot();
        static::creating(function($user){
            $user->activation_token = Str::random(30);
        });
    }

    /**
     * 指明一个用户有多条微博
     * Author David
     * Date 2019-03-25
     * @var object
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * 微博首页动态查询
     * Author David
     * Date 2019-03-25
     * @var array
     */
    public function feed()
    {
        return $this->statuses()
                    ->orderBy('created_at','desc');
    }

    /**
     * 用户和粉丝表关联关系设定
     * Author David
     * Date 2019-03-26
     */
    public function followers(){
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }

    /**
     * 设定粉丝和被关注人的关联关系
     * Author David
     * Date 2019-03-26
     */
    public function followings(){
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }

    /**
     * 用户关注操作
     * Author David
     * Date 2019-03-26
     */
    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        return $this->followings()->sync($user_ids, false);
    }

    /**
     * 用户取消关注操作
     * Author David
     * Date 2019-03-26
     */
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        return $this->followings()->detach($user_ids);
    }


    /**
     * 是否关注某个用户
     * Author David
     * Date 2019-03-26
     */
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
