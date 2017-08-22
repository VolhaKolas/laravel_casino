<?php

namespace Casino\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class BreakController extends Controller
{
    public function post() {
        $tId = DB::table('users')->where('id',  Auth::id())->pluck('t_id');
        $tId = $tId[0];
        $usersCount =  DB::table('users')->leftJoin('tables', 'users.t_id', '=', 'tables.t_id')->
        where('users.u_answer', 1)->where('users.id', '!=', Auth::id())->pluck('id');
        DB::table('users')->where('id', Auth::id())->
            update(['t_id' => 1, 'u_offer' => 0, "u_answer" => 0]);
        if(1 == count($usersCount)) {
            DB::table('users')->where('id', $usersCount[0])->
            update(['t_id' => 1, 'u_offer' => 0, "u_answer" => 0]);
        }
        return redirect()->back();
    }
}
