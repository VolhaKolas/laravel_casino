<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 13.10.17
 * Time: 4:23
 */
namespace Casino\Classes\Game\Better;
class BigBlindBetterPlace extends BetterPlace
{
    public function __construct()
    {
        parent::__construct();
        $prevBetter = new SmallBlindBetterPlace();
        $this->prevBetter = $prevBetter->better();
    }

    public function better()
    {
        return $this->checkBetter($this->prevBetter);
    }
}