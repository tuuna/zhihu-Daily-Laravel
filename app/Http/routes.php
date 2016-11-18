<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

function rq($key = null, $default = null) {
    if(!$key) return Request::all();
    return Request::get($key,$default);
}

function paginate($page=1,$limit=16) {
    $limit = $limit ? : 16;
    $skip = ($page ? $page -1 : 0) *$limit;
    return [$limit,$skip];
}

function err($msg = null) {
    return ['status' => 0,'msg' => $msg];
}

function suc($data_to_merge = []) {
    $data =  ['status' => 1,'msg' => 'ok','data' => []];

    if($data_to_merge)
        $data['data'] = array_merge($data['data'],$data_to_merge);
    return $data;
}
function user_ins() {
    return $user = new App\User;
}

function question_ins() {
    return $question = new App\Question;
}

function answer_ins() {
    return $answer = new App\Answer;
}

function comment_ins() {
    return $comment = new App\Comment;
}


Route::any('/api',function () {
    return ['version' => 0.1];
});

Route::any('/api/user/signup',function () {
    return user_ins()->signup();
});

Route::any('/api/user/login', function () {
    return user_ins()->signin();
});

Route::any('/api/user/change_password' , function () {
    return user_ins()->change_password();
});

Route::any('/api/user/reset_password', function () {
    return user_ins()->reset_password();
});

Route::any('/api/user/validate_reset_password', function () {
    return user_ins()->validate_reset_password();
});

Route::any('/api/user/read', function () {
    return user_ins()->read();
});

Route::any('/api/user/logout', function () {
    return user_ins()->logout();
});

Route::any('/api/user/exist', function () {
    return user_ins()->is_exists();
});

Route::any('/api/question/add'  , function() {
    return question_ins()->add();
});

Route::any('/api/question/change' , function() {
    return question_ins()->change();
});

Route::any('/api/question/observe', function() {
    return question_ins()->observe();
});

Route::any('/api/question/remove', function () {
    return question_ins()-> remove();
});

Route::any('/api/answer/add' ,function () {
    return answer_ins()->add();
});

Route::any('/api/answer/change' , function() {
    return answer_ins()->change();
});

Route::any('/api/answer/read', function() {
    return answer_ins()->read();
});

Route::any('/api/answer/vote', function() {
    return answer_ins()->vote();
});

Route::any('/api/comment/add' , function() {
    return comment_ins()->add();
});

Route::any('/api/comment/read', function() {
    return comment_ins()->read();
});

Route::any('/api/comment/remove', function() {
   return comment_ins()->remove();
});

Route::any('/api/timeline', 'CommonController@timeline');

Route::get('/',function() {
    return view('index');
});


