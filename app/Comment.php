<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function add() {
        if(!user_ins()->is_logged_in()) return ['status' => 0,'msg' => '请先登陆'];

        if(!rq('content')) return ['status' => 0,'msg' => '评论内容为空'];

        if((!rq('question_id') && !rq('answer_id')) || (rq('question_id') && rq('answer_id')))
            return ['status' => 0,'msg' => '只能获取question_id或者answer_id'];

        if(rq('question_id')) {
            $question = question_ins()->find(rq('question_id'));

            if(!$question) return ['status' => 0,'msg' => '这个问题不存在'];

            $this->question_id = rq('question_id');
        } else {
            $answer = answer_ins()->find(rq('answer_id'));
            if(!$answer) return ['status' => 0,'msg' => '这个问题不存在'];

            $this->answer_id = rq('answer_id');

        }

        if(rq('reply_to')) {
            $target = $this->find(rq('reply_to'));
            if(!$target) return ['status' => 0,'msg' => 'target 不存在'];
            if($target->user_id == session('user_id')) return ['status' => 0,'msg' => '不能回复自己的评论'];
            $this->reply_to = rq('reply_to');
        }

        $this->content = rq('content');
        $this->user_id = session('user_id');

        return $this->save() ?
            ['status' => 1,'id' => $this->id] :
            ['status' => 0,'msg' => '添加评论失败'];
    }

    public function read() {
        if(!rq('question_id') && !rq('answer_id'))
            return ['status' => 0,'msg' => '只能获取question_id或answer_id'];

        if(rq('question_id')) {
            $question = question_ins()->find(rq('question_id'));

            if(!$question) return ['status' => 0,'msg' => '这个问题不存在'];

            $data = $this->where('question_id',rq('question_id'))->get();
        } else {
            $answer = answer_ins()->find(rq('answer_id'));

            if(!$answer) return ['status' => 0,'msg' => '这个回答不存在'];

            $data = $this->where('answer_id',rq('answer_id'))->get();
        }
        return  ['status' => 1,'data' => $data->keyBy('id')];
    }

    public function remove() {
        if(!user_ins()->is_logged_in())
            return ['status' => 0,'msg' => '请先登陆'];

        if(!rq('id'))
            return ['status' => 0,'msg' => '未传要删除的评论id'];

        $comment = comment_ins()->find(rq('id'));
        if(!$comment) return ['status' => 0,'msg' => '这条评论不存在'];

        if($comment->user_id != session('user_id'))
            return ['status' => 0,'msg' => '拒绝访问'];

        $this->where('reply_to' ,rq('id'))->delete();

        return $comment->delete() ?
            ['status' => 1,'msg' => '删除成功'] :
            ['status' => 0,'msg' => '删除失败'];
    }
}
