<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Mail;
use Auth;

class UsersController extends Controller
{

    public function __construct(){
        $this->middleware('auth',[
            'except'=>['show','create','store','index','confirmEmail']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    //定义create方法

    public function create(){
        return view('users.create');
    }

    //定义show方法

    public function show(User $user){
        return view('users.show', compact('user'));
    }

    //定义stroe方法

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' =>'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
/**
        Auth::login($user);
        session()->flash('success','注册成功，欢迎加入，开始你的新旅程');
        return redirect()->route('users.show',[$user]);
**/

        $this->sendEmailConfirmationTo($user);
        session()->flash('success','激活确认邮件已发送，请查询你的邮箱');
        return redirect('/');
    }

    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    public function update(User $user, Request $request)
    {
        $this->validate($request,[
            'name' =>'required|max:50',
            'password' =>'nullable|confirmed|min:6'
        ]);

        $this->authorize('update',$user);

        $data=[];
        $data['name']=$request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        return redirect()->route('users.show',$user->id);
    }

    public function index()
    {
        $users = User::paginate(5);
        return view('users.index',compact('users'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('success','删除用户成功');
        return back();
    }

    //发送确认激活邮件的方法

    public function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'aufree@yousails.com';
        $name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    //通过邮件激活账户

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜，激活成功');
        return redirect()->route('users.show',[$user]);
    }

    //发送重置密码通知

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new(ResetPassword($token)));
    }
}