<?php

namespace App\Http\Controllers;

use App\Table_user;
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

            //check on $add is equal to at least one element of the array
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
        $numbers = self::arr();
        $priority = \App\Priority::priority($numbers);

        return view('holdem.holdem', compact('numbers', 'priority'));
    }


}
