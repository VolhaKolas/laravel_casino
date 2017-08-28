<?php

namespace Casino\Http\Controllers;

use Casino\TableId;
use Casino\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class AnswerController extends Controller
{
    public function post(Request $request) {
        $answer = $request->all()['answer'];
        $user = (int) $request->all()['user'];
        //если сокет сработал раньше, чем юзер, приславший его, внес изменения в БД
        if(0 == $answer) {
            TableId::answer($user);
        }
        else {
            DB::table('users')->where('id', $user)->
            update(["u_answer" => 1]);
        }
        $t_id = User::where('id', Auth::id())->pluck('t_id')[0];
        if(1 == $t_id) { //если все пользователи отменили игру
            return 0;
        }
        else {
            $offer = DB::table('users')->leftJoin('tables', 'users.t_id', '=', 'tables.t_id')->
            where('users.u_offer', 1)->count();
            $ans = DB::table('users')->leftJoin('tables', 'users.t_id', '=', 'tables.t_id')->
            where('users.u_answer', 1)->count();
            if($offer == $ans) { //если все пользователи готовы играть
                return 2;
            }
            else { //если кого-то еще ждем
                return 1;
            }
        }
    }
}
