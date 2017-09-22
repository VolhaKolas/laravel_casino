<?php

namespace Casino\Http\Controllers;

use Casino\Classes\Calculation\Calculation;
use Casino\Classes\Game\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class NextController extends Controller
{
    /*
    1. If current better == last better
    1.1. Raise
    1.1.1. u_money - for Auth user.
    1.1.2. u_bet - for Auth user.
    1.1.3. t_money.
    1.1.4. u_current_better - for Auth user and for the next better.
    1.2. Next
    1.2.1. t_open.
    1.2.2. u_current_better - current better - small blind or if small blind fold - the next user after sm
    1.2.3. u_last_better - dealer will be the last better or if dealer fold - the previous user before dealer
    2. If current better != last better
    2.1. Raise
    2.1.1. u_money - for Auth user.
    2.1.2. u_bet - for Auth user.
    2.1.3. t_money.
    2.1.4. u_current_better - for Auth user and for the next better.
    2.1.5. u_last_better - for Auth user and for the previous last better.
    2.2. Next
    2.2.1. u_current_better - for Auth user and for the next better.
     */
    public function post(Request $request) {
        $keys = array_keys($request->all());
        if(in_array('raise', $keys) or in_array('next', $keys)) {

            $t_id = DB::table('users')->where('id', Auth::id())->pluck('t_id')[0]; //TODO-написать нормальный update
            $currentBetter = DB::table('users')->where('id', Auth::id())->pluck('u_place')[0];
            $lastBetter = DB::table('users')->where('t_id', $t_id)->where('u_last_better', 1)->pluck('u_place')[0];
            $inGamePlaces = DB::table('users')->where('t_id', function ($query) {
                $query->select('t_id')->from('users')->where('id', Auth::id());
            })->where('u_fold', 0)->pluck('u_place'); // all available places
            $tableMoney = DB::table('tables')->where('t_id', function ($query) {
                $query->select('t_id')->from('users')->where('id', Auth::id());
            })->pluck('t_money')[0];
            $arrayPlaces = [];
            foreach ($inGamePlaces as $inGamePlace) {
                $arrayPlaces = array_merge($arrayPlaces, [$inGamePlace]);
            }
            sort($arrayPlaces);

            if (in_array('raise', $keys)) {
                $maxBet = DB::table('users')->where('t_id', function ($query) {
                    $query->select('t_id')->from('users')->where('id', Auth::id());
                })->max('u_bet');
                $userBet = DB::table('users')->where('id', Auth::id())->pluck('u_bet')[0];
                $userMoney = DB::table('users')->where('id', Auth::id())->pluck('u_money')[0];
                $bet = $maxBet - $userBet + Players::BET;
                if ($userMoney - $bet < 0) { //чтобы user не ушел в минус
                    $bet = $userMoney;
                }
                DB::table('users')->where('id', Auth::id())->
                update(['u_bet' => $userBet + $bet, 'u_money' => $userMoney - $bet]);
                DB::table('tables')->where('t_id', $t_id)->//TODO-написать нормальный update
                update(['t_money' => $tableMoney + $bet]);

                //set new current better
                if($currentBetter == $lastBetter) {
                    if ($currentBetter == $arrayPlaces[count($arrayPlaces) - 1]) {
                        $newCurrentBetter = $arrayPlaces[0];
                    } else {
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
                }
                else {
                    if ($currentBetter == $arrayPlaces[count($arrayPlaces) - 1]) {
                        $newCurrentBetter = $arrayPlaces[0];
                    } else {
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
            else if (in_array('next', $keys)) {
                if($currentBetter == $lastBetter) {
                    $t_open = DB::table('tables')->where('t_id', $t_id)->pluck('t_open')[0];
                    $t_open = $t_open + 1;


                    DB::table('tables')->where('t_id', $t_id)->update(['t_open' => $t_open]);
                    $dealer = DB::table('users')->where('t_id', function ($query) {
                        $query->select('t_id')->from('users')->where('id', Auth::id());
                    })->where('u_dealer', 1)->pluck('id')[0];

                    if($t_open < 4) {
                        //the last better determine
                        if (in_array($dealer, $arrayPlaces)) {
                            $newLastBetter = $dealer;
                        } else if ($dealer < $arrayPlaces[0]) {
                            $newLastBetter = $arrayPlaces[count($arrayPlaces) - 1];
                        } else {
                            for($i = count($arrayPlaces) - 1; $i >= 0; $i--) {
                                if ($dealer > $arrayPlaces[$i]) {
                                    $newLastBetter = $arrayPlaces[$i];
                                    break;
                                }
                            }
                        }
                        //the current better determine
                        if ($newLastBetter == $arrayPlaces[count($arrayPlaces) - 1]) {
                            $newCurrentBetter = $arrayPlaces[0];
                        } else {
                            $key = array_search($newLastBetter, $arrayPlaces);
                            $newCurrentBetter = $arrayPlaces[$key + 1];
                        }
                        DB::table('users')->where('t_id', $t_id)
                            ->where('u_current_better', 1)->update(['u_current_better' => 0]);
                        DB::table('users')->where('t_id', $t_id)
                            ->where('u_last_better', 1)->update(['u_last_better' => 0]);
                        DB::table('users')->where('t_id', $t_id)
                            ->where('u_place', $newCurrentBetter)->update(['u_current_better' => 1]);
                        DB::table('users')->where('t_id', $t_id)
                            ->where('u_place', $newLastBetter)->update(['u_last_better' => 1]);
                    }
                    else {
                        $userCards = DB::table('users AS u')->
                            join('user_cards AS uc', 'uc.u_id', '=', 'u.id')->where('u.t_id', $t_id)->
                            where('u.u_fold', 0)->select('u.id', 'uc.uc_card')->get();
                        $commonCards = DB::table('tables')->
                            where('t_id', $t_id)->select('t_flop1', 't_flop2', 't_flop3', 't_turn', 't_river')->get()[0];
                        $arrayCards = [];
                        foreach ($userCards as $userCard) {
                            $count = 0;
                            foreach ($arrayCards as $key => $arrayCard) {
                                if($arrayCard[0] == $userCard->id) {
                                    $arrayCards[$key][7] = $userCard->uc_card;
                                    $count = 1;
                                }
                            }
                            if(0 == $count) {
                                $arrayCards = array_merge($arrayCards,
                                    [[$userCard->id, $userCard->uc_card, $commonCards->t_flop1,
                                        $commonCards->t_flop2, $commonCards->t_flop3,
                                        $commonCards->t_turn, $commonCards->t_river]]);
                            }
                        }
                        $arrayWinners = [];
                        $result = 0;
                        $lastResult = 0;
                        foreach ($arrayCards as $arrayCard) {
                            $arrayCalculate = [];
                            if($lastResult < $result) {
                                $lastResult = $result;
                            }
                            foreach ($arrayCard as $key1 => $card) {
                                if($key1 != 0) {
                                    $arrayCalculate = array_merge($arrayCalculate, [$card]);
                                }
                            }
                            $calculation = new Calculation($arrayCalculate);
                            $result = $calculation->priority();
                            if($result > $lastResult) {
                                $arrayWinners = [$arrayCard[0]];
                            }
                            else if($result == $lastResult) {
                                $arrayWinners = array_merge($arrayWinners, [$arrayCard[0]]);
                            }
                        }
                        $gain = round($tableMoney/count($arrayWinners), 2);
                        foreach ($arrayWinners as $arrayWinner) {
                            $winnerMoney = DB::table('users')->where('id', $arrayWinner)->pluck('u_money')[0];
                            $winnerMoney = $winnerMoney + $gain;
                            DB::table('users')->where('id', $arrayWinner)->
                                update(['u_money' => $winnerMoney]);
                        }
                        DB::table('tables')->where('t_id', $t_id)->update(['t_money' => 0]);
                    }
                }
                else {
                    if ($currentBetter == $arrayPlaces[count($arrayPlaces) - 1]) {
                        $newCurrentBetter = $arrayPlaces[0];
                    } else {
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
                }
            }
        }


        return redirect()->back();
    }
}
