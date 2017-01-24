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




        /*
        $table_id = auth()->user()->tableUsers->table_id;//current table
        $players = Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players
        $numberOfPlayers = count($players); //count of players


        $numbers = CreateArray::createArray($numberOfPlayers); //card's values
        $common = array_slice($numbers, -5, 5); // this array we put on the table(common array) (the last five numbers)
        $array = array_slice($numbers, 0, -5); //this array we give on hands (the first numbers except the last five numbers)


         //check table table_cards on we've given cards
         //First we put cards on table_cards and user_cards just one time
         //And it could be done by user who was put on table table_user the first
         //it's done for case if other users load page or somebody reload page, we don't put on table another cards


        //here we check bd on card's existence
        $free = auth()->user()->tableUsers->tableCards->id;


        if(count($free) == 0) { //this for don't give cards more than one time
            Table_card::insert([
                "table_id" => auth()->user()->tableUsers->table_id, "flop1" => $common[0], "flop2" => $common[1],
                "flop3" => $common[2], "turn" => $common[3], "river" => $common[4]
            ]);

            for ($i = 0; $i < count($array);) {
                User_card::insert([
                    ["user_id" => $players[$i/2], "card" => $array[$i], "user_place" => $i/2 + 1],
                    ["user_id" => $players[$i/2], "card" => $array[$i + 1], "user_place" => $i/2 + 1]
                ]);
                $i = $i + 2;
            }
        }


*/

        return view('holdem.holdem');
    }


}
