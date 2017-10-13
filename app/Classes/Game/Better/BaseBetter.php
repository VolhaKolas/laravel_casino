<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 13.10.17
 * Time: 0:41
 */
namespace Casino\Classes\Game\Better;

abstract class BaseBetter {
    abstract function smallBlind();
    abstract function bigBlind();
    abstract function currentBetter();
}