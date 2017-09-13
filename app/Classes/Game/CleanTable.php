<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 13.09.17
 * Time: 3:13
 */
namespace Casino\Classes\Game;
use Illuminate\Support\Facades\DB;
class CleanTable
{
    public static function clean() {
        DB::table('users')->update([
            't_id' => 1, 'u_offer' => 0, "u_answer" => 0, "u_dealer_card" => null,
            "u_place" => null, "u_dealer" => 0
        ]);
        DB::table('user_cards')->delete();
    }
}