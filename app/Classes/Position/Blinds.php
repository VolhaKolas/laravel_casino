<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 22.01.17
 * Time: 21:11
 */

namespace App\Classes\Position;


use App\Table_user;
use App\User_card;

class Blinds
{

    public static function blinds() {
        /*
        * Here we determine who will be the dealer.
        * Then who has Small Blind and Big Blind and who will make first bet
        */

        $table_id = auth()->user()->tableUsers->table_id;//current table
        $players = Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players
        $numberOfPlayers = count($players); //count of players

        $dealer = User_card::where('dealer', 1)->value('user_place');



        $smallBlind = $dealer + 1; //player with Small Blind
        $bigBlind = $dealer + 2; //player with Big Blind
        $firstBeter = $dealer + 3; //player who must do first bet

        //here we create position if we have only two players. In this case we have only SB and BB
        if ($numberOfPlayers == 2) {
            $smallBlind = $dealer;
            $firstBeter = $smallBlind;
            if($dealer == 2) {
                $bigBlind = 1;
            }
            else {
                $bigBlind = 2;
            }
            $dealer = 0;
        }
        //here we create correct position if dealer position is the last or on end of list
        else if ($numberOfPlayers - $dealer == 2) {
            $firstBeter = 1;
        }
        else if($numberOfPlayers - $dealer == 1) {
            $bigBlind = 1;
            $firstBeter = 2;
        }
        else if ($numberOfPlayers - $dealer == 0) {
            $smallBlind = 1;
            $bigBlind = 2;
            $firstBeter = 3;
        }

        return([$dealer, $smallBlind, $bigBlind, $firstBeter]);

    }
}