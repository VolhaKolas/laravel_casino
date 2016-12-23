<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TexasHoldemController extends Controller
{
    /*combination's priority:
 street flash - 1e+16
 square - 1e+14
 full house - 1e+12
 flesh - 1e+10
 street - 1e+8
 triple - 1e+6
 2 couples - 1e+4
 couple - 1e+2
 */


    /*
     * @method calculate, there is array numbers a flesh combination
     * @param {Array} of 7 numbers
     * @return {Number)
     */

    protected function flesh($array)
    {
        rsort($array); // Sort an array in reverse order
        $newArray = [];
        $result = 0;

        /*
        *Check an array on flesh between (2-14)
         * We merge a new array from all $array numbers between (2-14)
         * And then we check array count(length). If it 5 and more, we have a flesh.
        */

        foreach ($array as $ar) {
            if ($ar >= 2 and $ar <= 14) {
                $newArray = array_merge($newArray, [$ar]);
            }
        }

        /*
        *Check an array on flesh between (102-114)
         * We merge a new array from all $array numbers between (102-114)
         * And then we check array count(length). If it 5 and more, we have a flesh.
         * We trap here only if there isn't flesh between (2-14),
         * if there is we trap on step with result calculation.
        */

        if (count($newArray) < 5) {
            $newArray = [];
            foreach ($array as $ar) {
                if ($ar >= 102 and $ar <= 114) {
                    $newArray = array_merge($newArray, [$ar % 100]);
                }
            }
        }

        /*
        *Check an array on flesh between (202-214)
         * * We merge a new array from all $array numbers between (202-214)
         * And then we check array count(length). If it 5 and more, we have a flesh.
         * We trap here only if there aren't flesh between (2-14) and between (102-114),
         * if there is we trap on step with result calculation.
        */

        if (count($newArray) < 5) {
            $newArray = [];
            foreach ($array as $ar) {
                if ($ar >= 202 and $ar <= 214) {
                    $newArray = array_merge($newArray, [$ar % 100]);
                }
            }
        }

        /*
        *Check an array on flesh between (302-314)
         * * We merge a new array from all $array numbers between (302-314)
         * And then we check array count(length). If it 5 and more, we have a flesh.
         * We trap here only if there aren't flesh between (2-14)
         * and between (102-114) and between (202-214),
         * if there is we trap on step with result calculation.
        */

        if (count($newArray) < 5) {
            $newArray = [];
            foreach ($array as $ar) {
                if ($ar >= 302 and $ar <= 314) {
                    $newArray = array_merge($newArray, [$ar % 100]);
                }
            }
        }

        /*
         * We trap here if we have not any array with 5 or more members
         * between (2-14) or (102-114) or (202-214) or (302-314)
        */

        if (count($newArray) < 5) {
            $result = 0;
        }

        /*
         * We trap here if there is flesh array
         * Calculation: remember - we sort an array in reverse order
         * That is why the first member - is the largest, the last - the smallest.
         * We multiply first five numbers on numbers from 1e+10 to 1e+2,
         * Because to make correct priority we need to take into account all numbers.
         */
        else  {
            $streetFlash = self::street($newArray);
            if($streetFlash != 0) {
                $result = $streetFlash * 1e+8;
            }
            else {
                $result = $newArray[0] * 1e+10 + $newArray[1] * 1e+8 + $newArray[2] * 1e+6 +
                    $newArray[3] * 1e+4 + $newArray[4] * 1e+2;
            }
        }
        return $result;
    }

    /*
     * @method calculate, there is array numbers a street combination
     * @param {Array} of 7 numbers
     * @return {Number)
     */

    protected function street($array)
    {

        $rArray = [];
        $count = 0;
        $result = 0;
        $ace = 0;

        //We want to get numbers remainders of division on 100

        foreach ($array as $ar) {
            $rArray = array_merge($rArray, [$ar % 100]);
        }


        /*
         * There is a street combibation(the smallest combination),
         * which consists of A, 2, 3, 4, 5
         * We need to add this combination to calculation
         * That is why we add 1 to our array if array has 14(A) number
         */

        foreach ($array as $ar) {
            if ($ar == 14) {
                $rArray = array_merge($rArray, [1]);

                //announce an existence of ace
                $ace = 1;
            }
        }

        rsort($rArray); // Sort an array in reverse order


        /*
         * We determine existence of street combination between first five array members
         * And then we calculate quantity($count) of members which have a difference with last member equal - 1.
         * If we have this quantity equal - 4, it's mean we get a street array.
         * Then we must cut waste members.
         */
        for ($i = 1; $i <= 4; $i++) {
            if ($rArray[$i - 1] - $rArray[$i] == 1) {
                $count++;
            }
            if ($count == 4) {
                $rArray = array_slice($rArray, 0, 5);
            }
        }

        /*
        * We determine existence of street combination between second five array members
         * We write a count($rArray) - because method flesh is dermined on street (flesh array consists of 5 members,
         * if there is an ace in flesh array - from 6 members)
        */
        if ($count < 4 and count($rArray) > 5) {
            $count = 0;
            for($j = 2; $j <= 5; $j++) {
                if ($rArray[$j - 1] - $rArray[$j] == 1) {
                    $count++;
                }
                if ($count == 4) {
                    $rArray = array_slice($rArray, 1, 6);
                }
            }
        }

        /*
        * We determine existence of street combination between third five array members
        */

        if ($count < 4 and count($rArray) > 6) {
            $count = 0;
            for($z = 3; $z <= 6; $z++) {
                if ($rArray[$z - 1] - $rArray[$z] == 1) {
                    $count++;
                }
                if ($count == 4) {
                    $rArray = array_slice($rArray, 2, 7);
                }
            }
        }

        /*
        * We determine existence of street combination between fourth five array members if we have ace in array
         * and that is why we have 8 array members
        */

        if ($count < 4 and count($rArray) > 7) {
            $count = 0;
            for($y = 4; $y <= 7; $y++) {
                if ($rArray[$y - 1] - $rArray[$y] == 1) {
                    $count++;
                }
                if ($count == 4) {
                    $rArray = array_slice($rArray, 3, 8);
                }
            }
        }

        //For calculation street combination we need only first member
        //(Don't forget we have an array sorted in reverse order
        if ($count == 4) {
            $result = 1e+8 * $rArray[0];
        }
        return $result;
    }

    /*
 * @method calculate, is it array numbers a couple, triple, square combination
 * @param {Array} arr Array of random numbers
 * @return {Number) result
 * if combination is couple, return couple number * 1e+2,
 *
  * else if combination is 2 couples, return the largest number * 1e+4
  * plus the smallest(or the next after the largest, if 3 couple) * 1e+2,
  *
  * else if combination is triple, return triple number * 1e+6,
  *
  * else if combination is full house, return triple number * 1e+12
  * plus couple(or the largest couple) * 1e+10,
  *
  * else if combination is square, return square number * 1e+14,
  *
  * else return 0.
 */

    protected function couples($array)
    {
        $count1 = 0; //count for calculation first couple
        $count2 = 0; //count for calculation second couple
        $count3 = 0; //count for calculation third couple
        $support = 0;

        $match1 = 0; //here we put first couple
        $match2 = 0; //here we put second couple
        $match3 = 0; //here we put third couple

        $rArray = [];

        $result = 0;

        //We want to get numbers remainders of division on 100

        foreach ($array as $ar) {
            $rArray = array_merge($rArray, [$ar % 100]);
        }

        rsort($rArray); // Sort an array in reverse order

        /*
         * we put values to $count1, $count2, $count3,
         * $match1, $match2, $match3 if we have some couples
         */
        for($i = 1; $i < count($rArray); $i++) {

            /*
             * First we trap here when $match1 = 0
             * If we have a couple (condition: $rArray[$i] == $rArray[$i - 1] is true)
             * $match1 become = first couple number
             * else $match1 = 0 and it will be = 0 while we get no couples
             *
             * If we get couple we check next numbers on triple and square
             * (condition: $rArray[$i] == $match1)
             *
             * else we trap on line with $match2 where we will find the second couple the same way
             * And then we trap on line with $match3 where we will find the third  couple
             *
             * $count1, $count2, $count3 - is a counts that calculate the number of the same cards
             */
            if($rArray[$i] == $match1 or $match1 == 0) {
                if($rArray[$i] == $rArray[$i - 1]) {
                    $match1 = $rArray[$i];
                    $count1++;
                }
            }

            else if($rArray[$i] == $match2 or $match2 == 0) {
                if($rArray[$i] == $rArray[$i - 1]) {
                    $match2 = $rArray[$i];
                    $count2++;
                }
            }

            else if($rArray[$i] == $match3 or $match3 == 0) {
                if($rArray[$i] == $rArray[$i - 1]) {
                    $match3 = $rArray[$i];
                    $count3++;
                }
            }
        }


        /* possible combinations of count1, count2, count3:
     100 - couple
     110 - 2 couples
     111 - 3 couples (needs to be made to 110)
     200 - triple
     210 - full house
     211 - full house + couple (needs to be made to 210)
     220 - 2 triples or full house + one similar (needs to be made to 210)
     300 - square
     310 - square + couple (needs to be made to 300)
     320 - square + triple (needs to be made to 300)*/

        //here we made 111 to 110(2 couples) and 211 to 210(full house)
        if(($count1 == 1 or $count1 == 2) and $count2 == 1 and $count3 == 1) {
            $count3 = 0;
        }

        //here we made 121 to 211 for easy calculation and then to 210(full house)
        else if($count2 == 2 and $count1 == 1 and $count3 == 1) {
            $support = $match2;
            $match2 = $match1;
            $match1 = $support;
            $count1 = 2;
            $count2 = 1;
            $count3 = 0;
        }

        //here we made 112 to 211 for easy calculation and then to 210(full house)
        else if($count3 == 2 and $count1 == 1 and $count2 == 1) {
            $support = $match3;
            $match2 = $match1;
            $match1 = $support;
            $count1 = 2;
            $count3 = 0;
        }
        //here we made 220 to 210 (full house)
        else if($count1 == 2 and $count2 == 2 and $count3 == 0) {
            $count2 = 1;
        }
        //here we made 120 to 210 (full house)
        else if ($count1 == 1 and $count2 == 2 and $count3 == 0) {
            $support = $match1;
            $match1 = $match2;
            $match2 = $support;
            $count1 = 2;
            $count2 = 1;
        }

        //320 to 300 and 310 to 300
        else if($count1 == 3 and ($count2 == 2 or $count2 == 1)) {
            $count2 = 0;
        }
        //230 to 320 and then to 300 and 130 to 310 and then to 300
        else if($count2 == 3 and($count1 == 2 or $count1 == 1)) {
            $support = $match2;
            $match2 = $match1;
            $match1 = $support;
            $count1 = 3;
            $count2 = 0;
        }

        //square
        if ($count1 == 3) {
            $count1 = 1e+14;
        }
        //full house
        else if ($count1 == 2 and $count2 == 1) {
            $count1 = 1e+12;
            $count2 = 1e+10;
        }
        //triple
        else if ($count1 == 2 and $count2 == 0) {
            $count1 = 1e+6;
        }
        //2 couples
        else if ($count1 == 1 and $count2 == 1) {
            $count1 = 1e+4;
            $count2 = 1e+2;
        }
        //couple
        else if ($count1 == 1 and $count2 == 0) {
            $count1 = 1e+2;
        }

        $result = $match1 * $count1 + $match2 * $count2;
        return $result;
    }

    protected function priority($array) {
        $common = array_slice($array, -5, 5); // this array we put on the table(common array)
        $array = array_slice($array, 0, -5); //this array we give on hands
        $commonResult = []; //on this array we put all result calculation

        for($i = 0; $i < count($array)/2; $i++) {
            $result = 0;// result calculation for different arrays
            $text = ""; // text result (type of combination)

            //this full array get different players
            $arr = array_merge(array_slice($array, $i * 2, 2), $common);
            $hight1 = $arr[0] % 100; //first hight card {@number}
            $hight2 = $arr[1] % 100; //second hight card {@number}
            $flesh = self::flesh($arr); //flesh {@number}
            $street = self::street($arr); //street {@number}
            $couples = self::couples($arr); //couples {@number}

            if($flesh >= 1e+16) {
                $text = "street flash";
                $result = $flesh;
            }
            else if($couples >= 1e+14 and $couples < 1e+16) {
                $text = "square";
                $result = $couples;
            }
            else if($couples >= 1e+12 and $couples < 1e+14) {
                $text = "full house";
                $result = $couples;
            }
            else if($flesh >= 1e+10) {
                $text = "flash";
                $result = $flesh;
            }
            else if($street >= 1e+8 and $street < 1e+10) {
                $text = "street";
                $result = $street;
            }
            else if($couples >= 1e+6 and $couples < 1e+8) {
                $text = "triple";
                $result = $couples;
            }
            else if($couples >= 1e+4 and $couples < 1e+6) {
                $text = "two couples";
                $result = $couples;
            }
            else if($couples >= 1e+2 and $couples < 1e+4) {
                $text = "couple";
                $result = $couples;
            }
            else {
                $text = "hight card";
                if($hight1 > $hight2) {
                    $result = $hight1 + $hight2 * 1e-2;
                }
                else {
                    $result = $hight2 + $hight1 * 1e-2;
                }
            }
            $commonResult = array_merge($commonResult, [$result]);
        }
        return $commonResult;
    }

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


    public function game(Request $request) {
        $tables = DB::select('SHOW TABLES');
        $reg = '/table_([\d]+)/';
        $table = 'table_';
        $mat =[];
            foreach ($tables as $tab) {
                foreach ($tab as $t) {
                    if (preg_match($reg, $t, $matches)) {
                        $mat = array_merge($mat, [(int)$matches[1]]);
                    }
                }
            }

        if (count($mat) == 0) {
            $table = 'table_' . 1;
        }
        else {
            sort($mat);

            for ($i = 1; $i < count($mat); $i++) {
                if ($mat[$i] - $mat[$i - 1] > 1) {
                    $table = $table . ($i + 1);
                    break;
                }
            }
            if($mat[0] > 1) {
                $table = 'table_' . 1;
            }
            else if ($table == 'table_') {
                $table = $table . (count($mat) + 1);
            }
        }







        $numbers = self::arr();
        $priority = self::priority($numbers);
        return view('holdem.holdem', compact('numbers', 'priority'));
    }



}
