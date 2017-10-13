<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 15.09.17
 * Time: 2:02
 */
namespace Casino\Classes\Game;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class Dealer
{
    private $places;
    private $arrayPlaces = [];
    private $userCount;
    private $better;
    private $prevBetter;

    public function __construct()
    {
        $this->places =  DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->pluck('u_place');

        $this->userCount = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->count();

        $this->places();
    }

    private function places() {
        foreach ($this->places as $place) {
            $this->arrayPlaces = array_merge($this->arrayPlaces, [$place]);
        }
        sort($this->arrayPlaces);
    }

    private function better($prevPlace) {
        if ($prevPlace == $this->arrayPlaces[count($this->arrayPlaces) - 1]) {
            $this->better = $this->arrayPlaces[0];
        } else {
            foreach ($this->arrayPlaces as $key => $arrayPlace) {
                if($arrayPlace == $prevPlace) {
                    $this->better = $this->arrayPlaces[$key + 1];
                    break;
                }
            }
        }
        return $this->better;
    }

    public function smallBlind() {
        $this->prevBetter = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->where('u_dealer', 1)->pluck('u_place')[0];
        if($this->userCount > 2) { //if players quantity > 2
            return $this->better($this->prevBetter);
        }
        else {
            return $this->prevBetter;
        }
    }

    public function bigBlind() {
        $this->prevBetter = $this->smallBlind();
        return $this->better($this->prevBetter);
    }

    public function currentBetter() {
        $this->prevBetter = $this->bigBlind();
        return $this->better($this->prevBetter);
    }

}
