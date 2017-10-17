<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 13.10.17
 * Time: 4:27
 */
namespace Casino\Classes\Game\Better\BetterPlace;
class CurrentBetterPlace extends BetterPlace
{
    public function __construct()
    {
        parent::__construct();
        $prevBetter = new BigBlindBetterPlace();
        $this->prevBetter = $prevBetter->better();
    }

    public function better()
    {
        return $this->checkBetter($this->prevBetter);
    }
}