<?php

namespace Casino\Http\Controllers;

use Casino\TableId;
use Casino\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class AdmissionController extends Controller
{
    public function post() {
        DB::table('users')->where('id', Auth::id())->
        update(["u_answer" => 1]);

        $existCards = DB::table('users')->leftJoin('tables', 'users.t_id', '=', 'tables.t_id')->
        where('id', Auth::id())->pluck('u_dealer_card');
        $arrayCards = [];
        foreach ($existCards as $existCard) {
            $arrayCards = array_merge($arrayCards, [$existCard]);
        }
        $card = User::dealerCardCreation($arrayCards);
        User::where('id', Auth::id())->update(['u_dealer_card' => $card]);
        return redirect()->back();
    }
}
