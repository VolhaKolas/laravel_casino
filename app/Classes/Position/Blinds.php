<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 22.01.17
 * Time: 21:11
 */

namespace App\Classes\Position;


use App\Classes\Position\AllAvailablePlaces;
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

        $allAvailablePlaces = AllAvailablePlaces::places();

        if(isset($dealer)) {
            for ($i = 0; $i < count($allAvailablePlaces); $i++) {
                if ($allAvailablePlaces[$i] == $dealer) {
                    $dealerKey = $i;
                }
            }

            //player with Small Blind

            if ($dealerKey == count($allAvailablePlaces) - 1) {
                $smallBlind = $allAvailablePlaces[0];
                $smallBlindKey = 0;
            } else {
                $smallBlind = $allAvailablePlaces[$dealerKey + 1];
                $smallBlindKey = $dealerKey + 1;
            }


            //player with Big Blind
            if (isset($smallBlind)) {
                if ($smallBlindKey == count($allAvailablePlaces) - 1) {
                    $bigBlind = $allAvailablePlaces[0];
                    $bigBlindKey = 0;
                } else {
                    $bigBlind = $allAvailablePlaces[$smallBlindKey + 1];
                    $bigBlindKey = $smallBlindKey + 1;
                }


                //player who must do first bet
                if(isset($bigBlind)) {
                    if ($bigBlindKey == count($allAvailablePlaces) - 1) {
                        $firstBeter = $allAvailablePlaces[0];
                    } else {
                        $firstBeter = $allAvailablePlaces[$bigBlindKey + 1];
                    }
                }
            }
        }



        //here we create position if we have only two players. In this case we have only SB and BB
        if ($numberOfPlayers == 2) {
            $dealer = 0;
        }

        if(!isset($smallBlind)) {
            $smallBlind = 0;
        }
        if(!isset($bigBlind)) {
            $bigBlind = 0;
        }
        if(!isset($firstBeter)) {
            $firstBeter = 0;
        }

        return([$dealer, $smallBlind, $bigBlind, $firstBeter]);
    }


}