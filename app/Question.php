<?php

namespace App;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * 添加问题,Laravel Validation
     *
     */
    public function add() {
        if(!user_ins()->is_logged_in()) {
            return ['status' => 0, 'msg' =>  '请先登陆'];
        }

        if(!rq('title')) {
            return ['status' => 0,'msg'  =>  '请输入标题'];
        }

        $this->title = rq('title');

        $this->user_id = session('user_id');

        if(rq('desc')) {
            $this->desc = rq('desc');
        }



        $this->save() ?
            ['status' =>  1, 'id' => $this->id] :
            ['status' =>  0, 'msg' => '新增问答失败'];

    }

    /**
     * @return array
     * 更新问题模块
     */
    public function change() {
        $id = rq('id');

        if(!user_ins()->is_logged_in())
            return ['status' => 0,'msg' => '请先登陆'];

        if(!$id) {
            return ['status' => 0, 'msg' => '没有得到用户的id'];
        }

        $question = $this->find($id);

        if($question->user_id != session('user_id')) {
            return ['status' => 0,'msg' => '禁止修改'];
        }

        if(rq('title')) return $question->title = rq('title');
        if(rq('desc')) return $question->desc = rq('desc');

        return $question->save() ?
            ['status' => 1,'msg' => '更新问题成功'] :
            ['status' => 0,'msg' => '更新问题失败'];
    }

    public function read_by_user_id($user_id)
    {
        $user = user_ins()->find($user_id);
        if (!$user)
            return err('用户不存在');

        $r = $this
//            ->with('question')
            ->where('user_id', $user_id)
            ->get()
            ->keyBy('id');

        return suc($r->toArray());
    }

    /**
     * 查看问题模块
     */

    public function read() {

        if(rq('id'))
            return ['status' => 1,'data' => $this->find(rq('id'))];

            if (rq('user_id')) {
                $user_id = rq('user_id') === 'self' ?
                    session('user_id') :
                    rq('user_id');
                return $this->read_by_user_id($user_id);
            }


 /*       $limit = rq('limit') ? : 15;
        $skip = (rq('skip') ?  rq('skip')-1 : 0 ) *$limit;*/

        list($limit,$skip) = paginate(rq('limit'),rq('skip'));

        $datas =  $this->orderBy('created_at')->skip($skip)->limit($limit)->get()->keyBy('id');
        return ['status' => 1, 'data' => $datas];

    }

    /**
     * 删除问题模块
     */

    public function remove() {
        if(!user_ins()->is_logged_in())
            return ['status' => 0,'msg' =>'请先登陆'];

        if(!rq('id'))
            return ['status' => 0,'msg' => '需要一个id'];

        $question = $this->find(rq('id'));

        if(!$question) return ['status' => 0,'msg' => '找不到对应id的问题'];

        if(session('user_id') != $question->user_id) return ['status' => 0,'msg' => '拒绝请求'];

        return $question->delete() ?
            ['status' => 1] :
            ['status' => 0,'msg' => '删除失败'];

    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
