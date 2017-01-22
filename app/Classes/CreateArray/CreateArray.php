<?php
namespace App\Classes\CreateArray;

final class CreateArray
{
    final static function createArray($players = 2)
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
}