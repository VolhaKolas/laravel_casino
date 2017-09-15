<?php

namespace Casino\Http\Controllers;

use Casino\Classes\Game\Dealer;
use Casino\Classes\Game\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ContinueController extends Controller
{
    public function post() {
        $t_id = DB::table('users')->where('id', Auth::id())->pluck('t_id')[0];
        DB::table('users')->where('t_id', $t_id)->
            update(['u_dealer_card' => null]);
        //TODO-переписать запрос без подзапросов
        /*
        DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->update(['u_dealer_card' => null]); - не работает!!! */

        $money = DB::table('tables')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->pluck('t_money')[0];
        if(0 == $money) {
            $dealer = new Dealer();
            $smallBlindPlace = $dealer->smallBlind();
            $bigBlindPlace = $dealer->bigBlind(); //bigBlind == u_last_better, because of the biggest bet
            $currentBetter = $dealer->currentBetter();
            $moneySB = Players::MONEY - Players::BET / 2;
            $moneyBB = Players::MONEY - Players::BET;
            $moneyTable = Players::BET * 3 / 2;
            $t_id = DB::table('users')->where('id', Auth::id())->pluck('t_id')[0];
            //TODO-переписать update с нормальным t_id
            DB::table('users')->where('t_id', $t_id)
                ->where('u_place', $smallBlindPlace)->update(['u_money' => $moneySB, 'u_bet' => Players::BET/2]); //снимаю деньги со small blind
            DB::table('users')->where('t_id', $t_id)
                ->where('u_place', $bigBlindPlace)->
                update(['u_money' => $moneyBB, 'u_last_better' => 1, 'u_bet' => Players::BET]); //снимаю деньги со big blind
            DB::table('tables')->where('t_id', $t_id)->update(['t_money' => $moneyTable]); //ложу деньги на стол
            DB::table('users')->where('t_id', $t_id)
                ->where('u_place', $currentBetter)->update(['u_current_better' => 1]);
        }
        return redirect()->back();
    }
}
