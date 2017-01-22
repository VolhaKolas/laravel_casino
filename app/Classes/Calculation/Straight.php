<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 21.01.17
 * Time: 21:00
 */

namespace App\Classes\Calculation;


class Straight
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
     * @method calculate, there is array numbers a street combination
     * @param {Array} of 7 numbers
     * @return {Number)
     */

    final public static function straight($array)
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
}