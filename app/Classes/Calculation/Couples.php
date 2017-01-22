<?php
namespace App\Classes\Calculation;

final class Couples
{
    /*combination's priority:
straight flash - 1e+16
square - 1e+14
full house - 1e+12
flush - 1e+10
straight - 1e+8
triple - 1e+6
2 pairs - 1e+4
pair - 1e+2
*/


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

    final public static function couples($array)
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
}