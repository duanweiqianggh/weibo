<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{
    //登录状态检查
    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['show','create','store']
        ]);
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    //用户注册页
    public function create()
    {
    	return view('users.create');
    }

    //用户注册提交处理
    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        //此处注册成功后 直接为用户登录
        Auth::login($user);
        session()->flash('success','欢迎您加入大T博客');
        return redirect()->route('users.show',[$user]);
    }

    //个人信息展示页
    public function show(User $user)
    {
    	return view('users.show',compact('user'));
    }

    //个人信息编辑页
    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    //个人信息编辑提交处理

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request,[
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $data = [];
        $data['name'] = $request['name'];
        if($request->password)
        {
            $data['password'] = $request->password;
        }
        $user->update($data);
        session()->flash('success','个人资料更新成功!');
        return redirect()->route('users.show',$user->id);
    }

}


























