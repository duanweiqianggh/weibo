<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;
class UsersController extends Controller
{
    //登录状态检查
    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['show','create','store','index','confirmEmail']
        ]);
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    //用户列表显示页
    public function index(){
        $users = User::paginate(5);
        return view('users.index',compact('users'));
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
        //\DB::connection()->enableQueryLog();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        //$queries = \DB::getQueryLog();
        //dd($queries);die;
        //此处注册成功后 发送验证邮件到注册邮箱
        $this->sendEmailConfrmationto($user);
        session()->flash('success','验证邮件已发送至您的注册邮箱上，请注意查收！');
        return redirect('/');
    }

    /**
     * 负责发送邮件
     * Author David
     * Date 2019-03-25
     */
    private function sendEmailConfrmationto($user)
    {
        $view = 'emails.confrm';
        $data = compact('user');
        $to_email = $user->email;
        $subject = "感谢注册大T博客 请确认您的邮箱。";

        Mail::send($view,$data,function ($message) use ($to_email,$subject){
            $message->to($to_email)->subject($subject);
        });
    }

    /**
     * 获取一个用户的所有微博数据 并分页
     * Author David
     * Date 2019-03-25
     */
    public function show(User $user)
    {
        $statuses = $user->statuses()
                            ->orderBy('created_at','desc')
                            ->paginate(10);
        return view('users.show',compact('user','statuses'));
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

    //管理员删除用户处理
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success','成功删除用户！');
        return back();
    }

    //用户邮箱认证处理

    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();
        $user->activation_token = null;
        $user->activated = true;
        $user->email_verified_at = now();
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜您 认证成功');
        return redirect()->route('users.show',$user->id);
    }

    /**
     * 用户关注列表页
     * Author David
     * Date 2019-03-27
     */
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(20);
        $title = $user->name . '关注的人';
        return view('users.show_follow', compact('users','title'));
    }

    /**
     * 用户粉丝列表页
     * Author David
     * Date 2019-03-27
     */
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(20);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('users','title'));
    }
}


























