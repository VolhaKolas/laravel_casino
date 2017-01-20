<?php

namespace App\Http\Controllers;

use App\Priority;
use App\Table_card;
use App\Table_user;
use App\User_card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TexasHoldemController extends Controller
{

    protected function arr($players = 2)
    {

        $allnumbers = [
            2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
            102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114,
            202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 212, 213, 214,
            302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314
        ];

        $randArray = [
            $allnumbers[rand(0, 51)]
        ]; //first value of array

        for ($i = 1; $i < 5 + 2 * $players; $i++) {
            $add = [
                $allnumbers[rand(0, 51)]
            ];
            $randArray = array_merge($randArray, $add);

            //check $add on equal to at least one of the array elements
            for ($j = 0; $j < count($randArray); $j++) {
                if ($randArray[count($randArray) - 1] == $randArray[$j] and $j != count($randArray) - 1) {
                    $i--;
                    array_pop($randArray);
                }
            }
        }
        return $randArray;
    }


    public function game(Request $request)
    {
        $user_id = auth()->id();
        $table_id = Table_user::where('user_id', $user_id)->value('table_id');
        $players = Table_user::where('table_id', $table_id)->pluck("user_id");
        $numberOfPlayers = count($players);


        $numbers = self::arr($numberOfPlayers);
        $common = array_slice($numbers, -5, 5); // this array we put on the table(common array)
        $array = array_slice($numbers, 0, -5); //this array we give on hands

        $free = Table_card::where("table_id", $table_id)->select('id')->get();

        if(count($free) == 0) {
            Table_card::insert([
                "table_id" => $table_id, "flop1" => $common[0], "flop2" => $common[1],
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

        $cards = User_card::where("user_id", $user_id)->pluck("card");

        $ids = Table_user::where('table_id', $table_id)->pluck("id");
        $user = Table_user::where('user_id', $user_id)->value("id");

        $id = [];
        foreach ($ids as $ids) {
            $id = array_merge($id, [$ids]);
        }
        sort($id);
        $key = 0;
        for($j = 0; $j < count($id); $j++) {
            if($id[$j] == $user) {
                $key = $j;
                break;
            }
        }

        //Get all user's cards from user_cards. And determine whom has the largest priority (it's mean we get card % 100)
        $deal = User_card::pluck("user_id", "card");
        $highesCard = 0;
        foreach ($deal as $k=>$d) {
           if($highesCard < $k % 100) {
               $highesCard = $k % 100;
           }
        }

        $dealer = User_card::whereIn('card', [$highesCard, $highesCard + 100, $highesCard + 200, $highesCard + 300])->value('user_place');
        $countPlayers = count($players);

        $smallBlind = $dealer + 1;
        $bigBlind = $dealer + 2;
        $firstBeter = $dealer + 3;

        if ($countPlayers == 2) {
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
        else if ($countPlayers - $dealer == 2) {
            $firstBeter = 1;
        }
        else if($countPlayers - $dealer == 1) {
            $bigBlind = 1;
            $firstBeter = 2;
        }
        else if ($countPlayers - $dealer == 0) {
            $smallBlind = 1;
            $bigBlind = 2;
            $firstBeter = 3;
        }

        $tableMoney = Table_card::where('table_id', $table_id)->value('table_money');

        if($tableMoney == null) {
            $playerSB = User_card::where('user_place', $smallBlind)->value("user_id");
            $playerBB = User_card::where('user_place', $bigBlind)->value("user_id");

            Table_user::where('user_id', $playerSB)->decrement('money', 50);

            Table_user::where('user_id', $playerBB)->decrement('money', 100);

            Table_card::where('table_id', $table_id)->update([
                "table_money" => 150
            ]);

            $tableMoney = Table_card::where('table_id', $table_id)->value('table_money');
        }

        switch($dealer) {
            case 1: { $leftD = 65; $topD = 15; break;}
            case 2: { $leftD = 80; $topD = 20; break;}
            case 3: { $leftD = 80; $topD = 62; break;}
            case 4: { $leftD = 65; $topD = 67; break;}
            case 5: { $leftD = 35; $topD = 67; break;}
            case 6: { $leftD = 15; $topD = 62; break;}
            case 7: { $leftD = 15; $topD = 20; break;}
            case 8: { $leftD = 35; $topD = 15; break;}
        }


        switch($smallBlind) {
            case 1: { $leftSB = 65; $topSB = 15; break;}
            case 2: { $leftSB = 80; $topSB = 20; break;}
            case 3: { $leftSB = 80; $topSB = 62; break;}
            case 4: { $leftSB = 65; $topSB = 67; break;}
            case 5: { $leftSB = 35; $topSB = 67; break;}
            case 6: { $leftSB = 15; $topSB = 62; break;}
            case 7: { $leftSB = 15; $topSB = 20; break;}
            case 8: { $leftSB = 35; $topSB = 15; break;}
        }

        switch($bigBlind) {
            case 1: { $leftBB = 65; $topBB = 15; break;}
            case 2: { $leftBB = 80; $topBB = 20; break;}
            case 3: { $leftBB = 80; $topBB = 62; break;}
            case 4: { $leftBB = 65; $topBB = 67; break;}
            case 5: { $leftBB = 35; $topBB = 67; break;}
            case 6: { $leftBB = 15; $topBB = 62; break;}
            case 7: { $leftBB = 15; $topBB = 20; break;}
            case 8: { $leftBB = 35; $topBB = 15; break;}
        }



        return view('holdem.holdem', compact('cards', 'numberOfPlayers', 'key', 'leftD', 'topD',
            'leftSB', 'topSB', 'leftBB', 'topBB', 'tableMoney', 'firstBeter'));
    }


}
