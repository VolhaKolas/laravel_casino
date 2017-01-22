<?php
namespace App\Classes\Position;

class Position
/*
 * Positions of Dealer chip, Big Blind chip, Small Blind chip
 */

{
    public static function left($place) {
        switch($place) {
            case 1: { $left = 65; break;}
            case 2: { $left = 80; break;}
            case 3: { $left = 80; break;}
            case 4: { $left = 65; break;}
            case 5: { $left = 35; break;}
            case 6: { $left = 15; break;}
            case 7: { $left = 15; break;}
            case 8: { $left = 35; break;}
        }
        return $left;
    }

    public static function top($place) {
        switch($place) {
            case 1: { $top = 15; break;}
            case 2: { $top = 20; break;}
            case 3: { $top = 62; break;}
            case 4: { $top = 67; break;}
            case 5: { $top = 67; break;}
            case 6: { $top = 62; break;}
            case 7: { $top = 20; break;}
            case 8: { $top = 15; break;}
        }
        return $top;
    }
}