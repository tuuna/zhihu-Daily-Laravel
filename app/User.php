<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Request;
class User extends Model
{
    /**
     * @return array
     * 注册模块
     */
    public function signup() {

        $username = Request::get('username');
        $password = Request::get('password');
        /**
         * 检查用户名密码是否为空
         */

        if( !$username && !$password) {
            return ['status' => 0,'msg' => '用户名或密码不能为空'];
        }

        /**
         *  检查用户名是否存在
         */

        $user_exists = $this->where('username',$username)->exists();

        if($user_exists) {
            return ['status' => 0,'msg' => '用户名已存在'];
        }

        /**
         * 加密密码
         */

        $hashed_password = Hash::make($password);
        /**
         *  存入数据库
         */

        $this->password = $hashed_password;
        $this->username = $username;
        if($this->save()) {
            return ['status' => 1,'msg' => '用户注册成功','id' => $this->id];
        } else {
            return ['status' => 0, 'msg' => '用户注册失败'];
        }
    }

    /**
     * 登陆模块
     */

    public function login() {

        /**
         * 检查输入是否为空
         */
        $has_username_and_password = $this->has_username_and_password();

        if(!$has_username_and_password) {
            return ['status' => 0,'msg' => '用户名或密码不能为空'];
        }

        $username = $has_username_and_password[0];
        $password = $has_username_and_password[1];

        /**
         * 检查用户名是否存在
         */
        $user = $this->where('username',$username)->first();

        if(!$user) {
            return ['status' => 0, 'msg' => '用户不存在'];
        }

        /**
         * 检查用户密码是否正确
         */
        $hashed_password = $user->password;

        if(!Hash::check($password,$hashed_password)) {
            return ['status' => 0, 'msg' => '用户密码错误'];
        }

        /**
         * 将用户信息写入session
         */
        session()->put('username',$user->username);
        session()->put('user_id',$user->id);

        return ['status' => 1,'id' => $user->id];
    }

    /**
     * 检查用户名密码是否为空
     */
    public function has_username_and_password() {
        $username = Request::get('username');
        $password = Request::get('password');



        if( $username && $password) {
            return [$username,$password];
        } else {
            return false;
        }
    }

    /**
     * 用户登出
     */

    public function logout() {
        session()-> flash(); //销毁所有的session,这是最偷懒的做法，但是很多时候还是需要记录用户的一些信息，则可以用forget来指定值来删除session
        return redirect('/');
//        session()-> forget('username');
//        $username = session()-> pull('username');

//        session()-> set('parent.child','20'); //这样这实现session中的嵌套
    }

    /**
     * 检测用户是否登陆
     */
    public function is_logged_in() {
        return session('username') ? session('user_id') : false;
    }

    public function answers() {
        return $this->belongsToMany('App\Answer')->withPivot('vote')->withTimestamps();
    }


}
