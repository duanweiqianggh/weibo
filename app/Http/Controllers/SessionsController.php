<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class SessionsController extends Controller
{
    //自动验证规则
    public function __construct()
    {
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    //用户登录页
    public function create(){
    	return view('sessions.create');
    }

    //用户提交登录
    public function store(Request $request){
    	$credentials = $this->validate($request,[
    			'email' => 'required|email|max:255',
    			'password' => 'required'
    		]);
    	if(Auth::attempt($credentials,$request->has('remember'))){
    		session()->flash('success','欢迎回来');
    		$fallback = route('users.show',Auth::user());
    		return redirect()->intended($fallback);
    	}else{
    		session()->flash('danger','用户名或密码错误!');
    		return redirect()->back()->withInput();
    	}
    }

    //用户退出功能
    public function destroy(){
        Auth::logout();
        session()->flash('success','您已成功退出');
        return redirect()->route('login');
    }
}
