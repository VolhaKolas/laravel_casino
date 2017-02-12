<?php

namespace App\Http\Controllers;

use JavaScript;
use Illuminate\Support\Facades\View;
use App\Classes\CreateArray\CreateArray;
use App\Classes\Position\Blinds;
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
        $dealer = 0;
        foreach ($players as $player){
            if(auth()->id() > $player) {
                $place++;
            }
            if(User_card::where('user_id', $player)->value('dealer') == 1) {
                $dealer = 1;
            }
        }


        /*
         * Dealer creation
         */

        //Make sure we have no dealer



        //Only if we have no dealer, we create a new (we may have a dealer after user reload page)
        if(User_card::where('user_id', auth()->id())->value('card') == null and $dealer == 0) {

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

        JavaScript::put([
            'users' => Table_user::where('table_id', auth()->user()->tableUsers->table_id)->count(),
            'open' => Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open'),
            'userPlace' => auth()->user()->userCards[0]->user_place
        ]);

        return View::make('holdem.holdem');
    }


}
