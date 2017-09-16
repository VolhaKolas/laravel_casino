<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 12.09.17
 * Time: 2:20
 */
namespace Casino\Classes\Game;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class Players
{
    const BET = 100;
    const MONEY = 1000;

    public static function players() {
        if(self::gameContinue() > 0) {
            $players = DB::table('users')->where('t_id', function ($query) {
                $query->select('t_id')->from('users')->where('id', Auth::id());
            })->where('t_id', "!=", 1)->select('u_money', 'login', 'u_dealer_card', 'u_place', 'u_photo', 'id')->get();
        }
        else {
            $players = DB::table('users AS u')->join('user_cards AS uc', 'u.id', '=', 'uc.u_id')->
                where('u.t_id', function ($query) {
                    $query->select('t_id')->from('users')->where('id', Auth::id());
                })->where('u.t_id', "!=", 1)->select('u.u_money', 'u.login', 'uc.uc_card', 'u.u_place', 'u.u_photo', 'u.id')->get();
        }
        return $players;
    }

    public static function dealer() {
        $dealer = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->where('u_dealer', 1)->pluck('id');
        if(count($dealer) > 0) {
            return $dealer[0];
        }
        else {
            return 0;
        }
    }

    public static function currentBetter() {
        $currentBetter = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->where('u_current_better', 1)->select('id', 'login')->get();
        if(count($currentBetter) > 0) {
            return $currentBetter[0];
        }
        else {
            return 0;
        }
        return $currentBetter;
    }

    public static function gameContinue() {
        $dealerCards = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->where('u_dealer_card', '!=', null)->count();
        return $dealerCards;
    }

    public static function tableMoney() {
        return DB::table('tables')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->pluck('t_money')[0];
    }

    public static function currentBet() {
        $maxBet = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->max('u_bet');
        $currentBet = DB::table('users')->where('id', Auth::id())->pluck('u_bet')[0];
        return $maxBet - $currentBet;
    }

    public static function lastBetter() {
        return  DB::table('users')->where('id', Auth::id())->pluck('u_last_better')[0];
    }

    public static function checkMoney() {
        $user = DB::table('users')->where('id', Auth::id())->select('u_money', 'u_bet')->get();
        $maxBet = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->max('u_bet');
        $sum = $user[0]->u_money + $user[0]->u_bet - $maxBet - self::BET;
        return $sum;
    }
}