<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    //create方法
    public function create()
    {
        return view('sessions.create');
    }

    //store方法创建登录session

    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required|min:6'
        ]);

        if(Auth::attempt($credentials,$request->has('remember'))){
            //登录成功时的操作
            session()->flash('success','欢迎回来');
            return redirect()->intended(route('users.show',[Auth::user()]));
        }else {
            # code...
            session()->flash('danger','登录信息错误');
            return redirect()->back();
        }

        return;
    }

    //destroy方法，退出登录
    public function destroy(){
        Auth::logout();
        session()->flash('success', '已退出登录');
        return redirect('login');
    }
}