<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 12.02.17
 * Time: 20:47
 */

namespace App\Classes\Position;


use App\Table_user;
use App\User_card;

class AllAvailablePlaces
{
    public static function places() {
        $table_id = auth()->user()->tableUsers->table_id;//current table
        $players = Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players
        $allAvailablePlaces = [];
        foreach ($players as $player) {
            $currentUserPlace = User_card::where('user_id', $player)->value('user_place');
                $allAvailablePlaces = array_merge($allAvailablePlaces, [$currentUserPlace]);
        }
        sort($allAvailablePlaces);
        return $allAvailablePlaces;
    }
}