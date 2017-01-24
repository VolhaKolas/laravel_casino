<?php

namespace App\Http\Controllers;

use App\Classes\CreateArray\CreateArray;
use App\Priority;
use App\Table_card;
use App\Table_user;
use App\User_card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TexasHoldemController extends Controller
{


    public function game(Request $request)
    {
        $table_id = auth()->user()->tableUsers->table_id;//current table
        $players = Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players
        $numberOfPlayers = count($players); //count of players


        /*
         * User's places in the table user_cards
         */

        $place = 1;
        foreach ($players as $player){
            if(auth()->id() > $player) {
                $place++;
            }
        }


        /*
         * Dealer creation
         */

        //Make sure we have no dealer
        $dealer = User_card::where('dealer', 1)->value('user_place');


        //Only if we have no dealer, we create a new (we may have a dealer after user reload page)
        if(User_card::where('user_id', auth()->id())->value('card') == null) {

            $userCard = User_card::where('user_id', auth()->id())->value('card');;
            if($userCard == null) {
                $existingCards = [];
                foreach ($players as $player) {
                    $usersCards = User_card::where('user_id', $player)->value('card');
                    $existingCards = array_merge($existingCards, [$usersCards]);
                }
                $newNumber = CreateArray::create($existingCards);

                User_card::insert([
                    ["user_id" => auth()->id(), "card" => $newNumber, "user_place" => $place]
                ]);
            }


            $countPlayersWithCards = 0;
            foreach ($players as $p) {
                if($p == User_card::where('user_id', $p)->value('user_id')) {
                    $countPlayersWithCards++;
                }

            }

            //Get all user's cards from user_cards. And determine whom has the largest priority (it's mean we get card % 100)
            if($numberOfPlayers == $countPlayersWithCards) {
                $deal = User_card::pluck("user_id", "card");
                $highesCard = 0;
                foreach ($deal as $k => $d) {
                    if ($highesCard < $k % 100) {
                        $highesCard = $k % 100;
                    }
                }

                $dealer = User_card::whereIn('card', [$highesCard, $highesCard + 100, $highesCard + 200, $highesCard + 300])->value('user_place');
                User_card::where('user_place', $dealer)->update([
                    'dealer' => 1
                ]);
            }

        }





        return view('holdem.holdem');
    }


}
