<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CommonController extends Controller
{
    public function timeline() {
        list($limit,$skip) = paginate(rq('limit'),rq('skip'));

        $questions = question_ins()
                        ->with('user')
                        ->limit($limit)
                        ->skip($skip)
                        ->orderBy('created_at','desc')
                        ->get();

        $answers = answer_ins()
                        ->with('user')
                        ->with('users')
                        ->limit($limit)
                        ->skip($skip)
                        ->orderBy('created_at','desc')
                        ->get();

        $data = $questions->merge($answers);
        $data = $data->sortByDesc(function($item) {
            return $item->created_at;
        });

        $datas = $data->values()->all();
        return ['status' => 1, 'data' => $datas];

    }
}
