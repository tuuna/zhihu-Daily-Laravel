<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
class User extends Model
{
    public function signup() {
        /*
         * 检查用户名是否为空
         */

        if( !Request::get('username')) {
            return ['status' => 0,'msg' => '用户名不能为空'];
        }
    }
}
