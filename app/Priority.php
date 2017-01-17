<?php

namespace App;

use App\Couples;
use App\Flesh;
use App\Street;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
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
            $flesh = Flesh::flesh($arr); //flesh {@number}
            $street = Street::street($arr); //street {@number}
            $couples = Couples::couples($arr); //couples {@number}

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
}
