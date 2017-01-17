<?php

namespace App;

use App\Couples;
use App\Flush;
use App\Straight;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
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


    final public static function priority($array) {
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
            $flesh = Flush::flush($arr); //flesh {@number}
            $street = Straight::straight($arr); //street {@number}
            $couples = Couples::couples($arr); //couples {@number}

            if($flesh >= 1e+16) {
                //straight flush
                $result = $flesh;
            }
            else if($couples >= 1e+14 and $couples < 1e+16) {
                //square
                $result = $couples;
            }
            else if($couples >= 1e+12 and $couples < 1e+14) {
                //full house
                $result = $couples;
            }
            else if($flesh >= 1e+10) {
                //flush
                $result = $flesh;
            }
            else if($street >= 1e+8 and $street < 1e+10) {
                //straight
                $result = $street;
            }
            else if($couples >= 1e+6 and $couples < 1e+8) {
                //triple
                $result = $couples;
            }
            else if($couples >= 1e+4 and $couples < 1e+6) {
                //two pairs
                $result = $couples;
            }
            else if($couples >= 1e+2 and $couples < 1e+4) {
                //pair
                $result = $couples;
            }
            else {
                //hight card
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
}
