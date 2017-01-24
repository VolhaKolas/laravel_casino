<?php
namespace App\Classes\CreateArray;

final class CreateArray
{
    protected static $allnumbers = [
        2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
        102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114,
        202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 212, 213, 214,
        302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314
    ];

    public static function create(array $existingCards = []) {

        if($existingCards != []) {
            for ($i = 0; $i < 1;) {
                $i++;
                $randNumber = self::$allnumbers[rand(0, 51)]; //card which we add

                foreach ($existingCards as $exN) {
                    if ($exN == $randNumber) {
                        $i = 0;
                    }
                }
            }
        }
        else {
            $randNumber = self::$allnumbers[rand(0, 51)];
        }

        return $randNumber;
    }
}