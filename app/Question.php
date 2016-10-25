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
}
