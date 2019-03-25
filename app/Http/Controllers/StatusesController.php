<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class StatusesController extends Controller
{
    //请求过滤
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 用户发帖提交
     * Author David
     * Date 2019-03-25
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'content' => 'required|max:140'
        ]);
        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        session()->flash('success','发布成功');
        return redirect()->back();
    }
}
