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


        $numbers = CreateArray::createArray($numberOfPlayers); //card's values
        $common = array_slice($numbers, -5, 5); // this array we put on the table(common array) (the last five numbers)
        $array = array_slice($numbers, 0, -5); //this array we give on hands (the first numbers except the last five numbers)

        /*
         * check table table_cards on we've given cards
         * First we put cards on table_cards and user_cards just one time
         * And it could be done by user who was put on table table_user the first
         * it's done for case if other users load page or somebody reload page, we don't put on table another cards
        */

        //here we check bd on card's existence
        //$free = $table_id->tableCards->id;
        $free = auth()->user()->tableUsers->tableCards->id;
        //$free = Table_card::where("table_id", $table_id)->select('id')->get();

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


        /*
         * Here we determine who will be the dealer.
         * Then who has Small Blind and Big Blind
         */


        //Make sure we have no dealer, we need it on the steps other than the first
        $dealer = User_card::where('dealer', 1)->value('user_place');

        //Only if we have no dealer, we create a new
        if($dealer == null) {

            //Get all user's cards from user_cards. And determine whom has the largest priority (it's mean we get card % 100)
            $deal = User_card::pluck("user_id", "card");
            $highesCard = 0;
            foreach ($deal as $k=>$d) {
                if($highesCard < $k % 100) {
                    $highesCard = $k % 100;
                }
            }

            $dealer = User_card::whereIn('card', [$highesCard, $highesCard + 100, $highesCard + 200, $highesCard + 300])->value('user_place');
            User_card::where('user_place', $dealer)->update([
                'dealer' => 1
            ]);
        }



        $smallBlind = $dealer + 1; //player with Small Blind
        $bigBlind = $dealer + 2; //player with Big Blind
        $firstBeter = $dealer + 3; //player who must do first bet

        //here we create position if we have only two players. Ib this case we have only SB and BB
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

        $firstBeterId = \App\User_card::where('user_place', $firstBeter)->value('user_id');

        /*
         * Here we take money from SB user and BB user and put this money on the table
         */
        $tableMoney = auth()->user()->tableUsers->tableCards->table_money;

        if($tableMoney == null) { // this for don't take and put money more than one time
            $playerSB = User_card::where('user_place', $smallBlind)->value("user_id");
            $playerBB = User_card::where('user_place', $bigBlind)->value("user_id");

            Table_user::where('user_id', $playerSB)->decrement('money', 50);

            Table_user::where('user_id', $playerBB)->decrement('money', 100);

            Table_card::where('table_id', $table_id)->update([
                "table_money" => 150
            ]);
        }

        /*
         * Here we determine positions of dealer chip, SB chip and BB chip
         */




        return view('holdem.holdem', compact('numberOfPlayers', 'dealer', 'smallBlind', 'bigBlind', 'firstBeter', 'firstBeterId'));
    }


}
