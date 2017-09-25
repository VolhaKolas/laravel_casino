<?php

namespace Casino\Http\Controllers;

use Casino\Classes\Game\Dealer;
use Casino\Classes\Game\Players;
use Casino\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class NextGameController extends Controller
{
    public function post() {
        if(Players::open() >= 4) {
            $t_id = DB::table('users')->where('id', Auth::id())->pluck('t_id')[0];
            $dealer = DB::table('users')->where('t_id', $t_id)->where('u_dealer', 1)->pluck('u_place')[0];
            $inGamePlaces = DB::table('users')->where('t_id', $t_id)
                ->where('u_money', ">", 50)->pluck('u_place'); // all available places
            if(count($inGamePlaces) > 1) { //если еще по крайней мере у двух юзеров есть деньги
                $losers = DB::table('users')->where('t_id', $t_id)
                    ->where('u_money', "<", 100)->pluck('id');
                $arrayPlaces = [];
                foreach ($inGamePlaces as $inGamePlace) {
                    $arrayPlaces = array_merge($arrayPlaces, [$inGamePlace]);
                }
                sort($arrayPlaces);
                $newDealer = $arrayPlaces[0]; //if $dealer == the last place (>= $arrayPlaces[count($arrayPlaces) - 1])
                if ($dealer < $arrayPlaces[count($arrayPlaces) - 1]) {
                    foreach ($arrayPlaces as $key => $arrayPlace) {
                        if($arrayPlace > $dealer) {
                            $newDealer = $arrayPlace;
                            break;
                        }
                    }
                }

                //убрала всех сбросивших карты, старого дилера, старого последнего юзера, старого текущего юзера
                DB::table('users')->where('t_id', $t_id)->update(['u_fold' => 0, 'u_bet' => 0,
                    'u_dealer' => 0, 'u_current_better' => 0, 'u_last_better' => 0]);


                $numberOfCards = 5 + 2 * count($inGamePlaces);
                $newCards = User::cardsCreation([], $numberOfCards); //массив с новыми картами
                DB::table('tables')->where('t_id', $t_id)->
                    update(['t_money' => 0, 't_flop1' => $newCards[0], 't_flop2' => $newCards[1], 't_flop3' => $newCards[2],
                't_turn' => $newCards[3], 't_river' => $newCards[4], 't_open' => 0]);

                DB::table('user_cards AS uc')->join('users AS u', 'uc.u_id', '=', 'u.id')->
                where('u.t_id', $t_id)->delete();
                $count = 5;
                foreach ($inGamePlaces as $inGamePlace) {
                    $u_id = DB::table('users')->where('t_id', $t_id)->where('u_place', $inGamePlace)->pluck('id')[0];
                    DB::table('user_cards')->insert(['u_id' => $u_id, 'uc_card' => $newCards[$count]]);
                    $count++;
                    DB::table('user_cards')->insert(['u_id' => $u_id, 'uc_card' => $newCards[$count]]);
                    $count++;
                }

                DB::table('users')->where('t_id', $t_id)->where('u_place', $newDealer)->
                update(['u_dealer' => 1]); //установила дилера

                $dealer = new Dealer();
                $smallBlindPlace = $dealer->smallBlind();
                $bigBlindPlace = $dealer->bigBlind(); //bigBlind == u_last_better, because of the biggest bet
                $currentBetter = $dealer->currentBetter();

                $moneySB = DB::table('users')->where('u_place', $smallBlindPlace)->
                    where('t_id', $t_id)->pluck('u_money')[0] - Players::BET / 2;
                $moneyBB = DB::table('users')->where('u_place', $bigBlindPlace)->
                    where('t_id', $t_id)->pluck('u_money')[0] - Players::BET;
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

                if(count($losers) > 0) {
                    foreach ($losers as $loser) {//убрала всех не имеющих средств юзеров
                        DB::table('users')->where('id', $loser)->
                        update(['u_place' => null, 'u_money' => Players::MONEY, 'u_bet' => 0, 'u_dealer' => 0,
                            'u_current_better' => 0, 'u_last_better' => 0, 'u_fold' => 0, 'u_offer' => 0, 'u_answer' => 0,
                            't_id' => 1]);
                        DB::table('user_cards')->where('u_id', $loser)->delete();
                    }
                }
            }
            else {//если остался один юзер с деньгами, удаляем всех нахрен
                $users = DB::table('users')->where('t_id', $t_id)->pluck('id');
                foreach ($users as $user) {
                    DB::table('users')->where('id', $user)->
                    update(['u_place' => null, 'u_money' => Players::MONEY, 'u_bet' => 0, 'u_dealer' => 0,
                        'u_current_better' => 0, 'u_last_better' => 0, 'u_fold' => 0, 'u_offer' => 0, 'u_answer' => 0,
                        't_id' => 1]);
                    DB::table('user_cards')->where('u_id', $user)->delete();
                }
                DB::table('tables')->where('t_id', $t_id)->delete();
            }
        }
        return redirect()->back();
    }
}
