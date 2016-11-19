<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function add() {
        if(!user_ins()->is_logged_in()) return ['status' => 0,'msg' => '请先登陆'];

        if(!rq('question_id') || !rq('content')) return ['status' => 0,'msg' => '接收问题号和内容失败'];

        $question = question_ins()->find(rq('question_id'));

        if(!$question) return ['status' => 0,'msg' => '问题不存在'];

        $answered = $this->where(['question_id' => rq('question_id'),'user_id' => session('user_id')])->count();

        if($answered) return ['status' => 0,'msg' => '一个问题不能多次回答'];

        $this->question_id = rq('question_id');
        $this->content = rq('content');
        $this->user_id = session('user_id');

        $this->save() ?
            ['status' => 1, 'msg' => '问题发布成功'] :
            ['status' => 0, 'msg' => '问题发布失败'];
    }

    public function change() {
        if(!user_ins()->is_logged_in()) return ['status' => 0,'msg' => '请先登陆'];

        if(!rq('id') || !rq('content')) return ['status' => 0,'msg' => '接收回答号和内容失败'];

        $answer = answer_ins()->find(rq('id'));

        if(!$answer) return ['status' => 0,'msg' => '回答不存在'];

        $answer->content = rq('content');

        return $answer->save() ?
            ['status' => 1,'msg' => '修改成功'] :
            ['status' => 0,'msg' => '修改失败'];
    }

    public function read() {
        if(!rq('id') || !rq('question_id')) return ['status' => 0,'msg' =>'未接收对应的问题号和回答号'];

        if(rq('id')) {
            $answer = answer_ins()->find(rq('id'));

            if(!$answer) return ['status' => 0,'msg' => '没有对应的答案'];

            return ['status' => 1,'data' => $answer];
        }

        if(!question_ins()->find(rq('question_id'))) return ['status' => 0,'msg' => '没有对应的问题'];
        $answers = $this->where('question_id',rq('question_id'))->get()->keyBy('id');

        return ['status' => 1,'data' => $answers];
    }

    public function vote() {
        if(!user_ins()->is_logged_in())
            return ['status' => 0,'msg' => '请先登陆'];

        if(!rq('id') || !rq('vote'))
            return ['status' => 0,'msg' => '需要传入问题id和投票状态'];

        $answer = $this->find(rq('id'));

        if(!$answer) return ['status' => 0,'msg' => '没有这个问题'];

        $vote = $answer
                    ->users() //返回一个关系
                    ->newPivotStatement() //进入中间表
                    ->where('user_id',session('user_id'))
                    ->where('answer_id',rq('id'))
                    ->delete();


        $answer->users()->attach(session('user_id'),['vote' => (int) rq('vote')]);

        return ['status' => 1];
    }

    /**
     * 表之间的关系
     *
     *
    */
     public function users() {
        return $this->belongsToMany('App\User')->withPivot('vote')->withTimestamps();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
