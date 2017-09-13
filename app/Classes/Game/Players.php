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
    public static function players() {
        $players = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->where('t_id', "!=", 1)->select('u_money', 'login', 'u_dealer_card', 'u_place' , 'u_photo', 'id')->get();
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
}