<?php

namespace App;

use App\Street;
use Illuminate\Database\Eloquent\Model;

class Flesh extends Model
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

    final public static function flesh($array)
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
            $streetFlash = Street::street($newArray);
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
}
