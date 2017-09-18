<?php

namespace Casino\Http\Controllers;

use Casino\Classes\Game\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class BetController extends Controller
{
    /*
    Columns to change:
    1. Fold
    1.1. u_fold - for Auth user.
    1.2. u_current_better - for Auth user and for the next better.
    2. Call
    2.1. u_money - for Auth user.
    2.2. u_bet - for Auth user.
    2.3. t_money.
    2.4. u_current_better - for Auth user and for the next better.
    3. Raise
    3.1. u_money - for Auth user.
    3.2. u_bet - for Auth user.
    3.3. t_money.
    3.4. u_current_better - for Auth user and for the next better.
    3.5. u_last_better - for Auth user and for the previous last better.
    */
    public function post(Request $request)
    {
        $keys = array_keys($request->all());
        if (in_array('fold', $keys) or in_array('call', $keys) or in_array('raise', $keys)) {
            $t_id = DB::table('users')->where('id', Auth::id())->pluck('t_id')[0]; //TODO-написать нормальный update
            $currentBetter = DB::table('users')->where('id', Auth::id())->pluck('u_place')[0];
            $maxBet = DB::table('users')->where('t_id', function ($query) {
                $query->select('t_id')->from('users')->where('id', Auth::id());
            })->max('u_bet');
            $userBet = DB::table('users')->where('id', Auth::id())->pluck('u_bet')[0];
            $userMoney = DB::table('users')->where('id', Auth::id())->pluck('u_money')[0];
            $tableMoney = DB::table('tables')->where('t_id', function ($query) {
                $query->select('t_id')->from('users')->where('id', Auth::id());
            })->pluck('t_money')[0];
            if ($maxBet != $userBet) {
                if (in_array('call', $keys)) {
                    $bet = $maxBet - $userBet;
                } else if (in_array('raise', $keys)) {
                    $bet = $maxBet - $userBet + Players::BET;
                }



                if (in_array('fold', $keys)) {
                    DB::table('users')->where('id', Auth::id())->update(['u_fold' => 1]);

                    $countUserNotFold = DB::table('users')->where('t_id', $t_id)->
                        where('u_fold', 0)->count();
                    if (1 == $countUserNotFold) {
                        $userNotFoldMoney = DB::table('users')->where('t_id', $t_id)->
                        where('u_fold', 0)->pluck('u_money')[0];
                        $userNotFoldMoney = $userNotFoldMoney + $tableMoney;
                        DB::table('users')->where('t_id', $t_id)->
                        where('u_fold', 0)->update(['u_money' => $userNotFoldMoney]);
                        DB::table('tables')->where('t_id', $t_id)->update(['t_open' => 4, 't_money' => 0]);
                    }

                } else if (in_array('call', $keys) or in_array('raise', $keys)) { //set money

                    if($userMoney - $bet < 0) { //чтобы user не ушел в минус
                        $bet = $userMoney;
                    }
                    DB::table('users')->where('id', Auth::id())->
                    update(['u_bet' => $userBet + $bet, 'u_money' => $userMoney - $bet]);
                    DB::table('tables')->where('t_id', $t_id)->//TODO-написать нормальный update
                    update(['t_money' => $tableMoney + $bet]);
                }

                //set new current better as for fold, as for call, as for raise
                $inGamePlaces = DB::table('users')->where('t_id', function ($query) {
                    $query->select('t_id')->from('users')->where('id', Auth::id());
                })->where('u_fold', 0)->pluck('u_place');
                $arrayPlaces = [];
                foreach ($inGamePlaces as $inGamePlace) {
                    $arrayPlaces = array_merge($arrayPlaces, [$inGamePlace]);
                }
                sort($arrayPlaces);
                $newCurrentBetter = $arrayPlaces[0];
                if ($currentBetter != $arrayPlaces[count($arrayPlaces) - 1]) {
                    foreach ($arrayPlaces as $arrayPlace) {
                        if ($arrayPlace > $currentBetter) {
                            $newCurrentBetter = $arrayPlace;
                            break;
                        }
                    }
                }
                DB::table('users')->where('id', Auth::id())->
                update(['u_current_better' => 0]);
                DB::table('users')->where('t_id', $t_id)-> //TODO-сделать нормальный update
                where('u_place', $newCurrentBetter)->update(['u_current_better' => 1]);

                //set user as the last better for raise
                if (in_array('raise', $keys)) {
                    DB::table('users')->where('t_id', $t_id)->where('u_last_better', 1)->update(['u_last_better' => 0]);
                    DB::table('users')->where('id', Auth::id())->update(['u_last_better' => 1]); //set new last better
                }
            }
        }
        return redirect()->back();
    }
}
