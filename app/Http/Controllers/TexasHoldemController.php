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
                    ["user_id" => $players[$i/2], "card" => $array[$i]],
                    ["user_id" => $players[$i/2], "card" => $array[$i + 1]]
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

        return view('holdem.holdem', compact('cards', 'numberOfPlayers', 'key'));
    }


}
