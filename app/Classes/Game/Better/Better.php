<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 13.10.17
 * Time: 3:35
 */
namespace Casino\Classes\Game\Better;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class Better extends BaseBetter
{
    public function smallBlind() {
        return new SmallBlindBetterPlace();
    }

    public function bigBlind() {
        return new BigBlindBetterPlace();
    }

    public function currentBetter() {
        return new CurrentBetterPlace();
    }
}