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
}
