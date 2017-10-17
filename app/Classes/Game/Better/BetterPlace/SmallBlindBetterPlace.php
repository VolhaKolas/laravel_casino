<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 13.10.17
 * Time: 4:14
 */
namespace Casino\Classes\Game\Better\BetterPlace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class SmallBlindBetterPlace extends BetterPlace
{
    private $userCount;

    public function __construct() {
        parent::__construct();
        $this->userCount = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->count();

        $this->prevBetter = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->where('u_dealer', 1)->pluck('u_place')[0];

    }

    public function better()
    {
        if($this->userCount > 2) { //if players quantity > 2
            return $this->checkBetter($this->prevBetter);
        }
        else {
            return $this->prevBetter;
        }
    }
}