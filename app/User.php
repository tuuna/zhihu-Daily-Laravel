<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Request;
class User extends Model
{

    /**
     * 获取用户信息
     */

    public function read() {
        if(!rq('id'))
            return err('需要传递一个id');

        $get = ['id','username','avatar_url','intro'];
        $user = $this->find(rq('id'),$get);
        $data = $user->toArray();
        $answer_count = answer_ins()->where('user_id',rq('id'))->count();
        $question_count = question_ins()->where('user_id',rq('id'))->count();
        /*$answer_count = $user->answers()->count();
        $question_count = $user->questions()->count();*/
        $data['answer_count'] = $answer_count;
        $data['question_count'] = $question_count;

        return suc($data);
    }
    /**
     * @return array
     * 注册模块
     */
    public function signup() {

        $username = Request::get('username');
        $password = Request::get('password');
        /**
         *
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

    /**
     * @return array
     * 修改密码
     */
    public function change_password() {
        if(!$this->is_logged_in())
            return ['status' => 0 , 'msg' => '请先登陆'];

        if(!rq('old_password') || !rq('new_password'))
            return ['status' => 0,'msg' => '需要输入旧密码或新密码'];

        $user = $this->find(session('user_id'));

        if(!Hash::check(rq('old_password'),$user->password))
            return ['status' => 0 , 'msg' => '旧密码错误或无效'];

        $user->password = bcrypt(rq('new_password'));
        return $user->save() ?
            ['status' => 1, 'msg' => '密码修改成功'] :
            ['status' => 0, 'msg' => '密码修改失败'];
    }

    /**
     * 找回(重置)密码
     */

    public function reset_password() {

        if($this->is_robert())
            return err('您的操作频率太快了');

        if(!rq('phone'))
            return err('需要先输入您正确的手机号码');

        $user = $this->where('phone',rq('phone'))->first();

        if(!$user)
            return err('这个手机号不存在');

        $captcha = $this->generate_captcha();
        $this->send_msg();
        $user->phone_captcha = $captcha;

        if($user->save()) {
            $this->send_msg();
            session()->set('last_sms_time',time());
            return suc();
        }
        return err('验证失败');
    }

    /**
     * 短信发送api
     */

    public function send_msg() {
        return true;
    }
    /**
     * 生成验证码
     */

    public function generate_captcha() {
        return rand(1000,9999);
    }

    /**
     * @param int $time
     * @return bool
     * 检查是否暴力发送
     */

    public function is_robert($time = 10) {
        $current_time = time();
        $last_sms_time = session('last_sms_time');

        if($current_time - $last_sms_time < $time)
            return true;
    }

    /**
     * 验证找回密码api
     */

    public function validate_reset_password() {

        if($this->is_robert(2))
            return err('您的操作频率太快了');

        if (!rq('phone') || !rq('phone_captcha') || !rq('new_password'))
            return err('请先输入电话号码验证码以及新的密码');

        $user = $this->where([
            'phone' => rq('phone'),
            'phone_captcha' => rq('phone_captcha')
        ])->first();

        if(!$user)
            return err('invalid phone or invalid phone_captcha');

        $user->password = bcrypt(rq('new_password'));
        return $user->save() ?
            suc() :
            err('密码更新失败');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * 回答关联表建立
     */
    public function answers() {
        return $this->belongsToMany('App\Answer')->withPivot('vote')->withTimestamps();
    }

    /**
     * 问题关联表建立
     */
    public function questions() {
        return  $this->belongsToMany('App\Question')->withPivot('vote')->withTimestamps();
    }

    /**
     * 检查用户名是否存在
     *
     */

    public function is_exists() {
        return suc(['count' => $this->where(rq())->count()]);
    }
}
