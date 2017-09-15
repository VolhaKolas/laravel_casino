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
    private $dealer;
    private $places;
    private $arrayPlaces = [];
    private $userCount;

    public function __construct()
    {
        $this->dealer = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->where('u_dealer', 1)->select('id', 'u_place', 't_id')->get()[0];

        $this->places =  DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->pluck('u_place');

        $this->userCount = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->count();

        foreach ($this->places as $place) {
            $this->arrayPlaces = array_merge($this->arrayPlaces, [$place]);
        }
        sort($this->arrayPlaces);
    }

    public function smallBlind() {
        if($this->userCount > 2) { //if players quantity > 2
            if ($this->dealer->u_place == $this->arrayPlaces[count($this->arrayPlaces) - 1]) {
                $smallBlind = $this->arrayPlaces[0];
            } else {
                $smallBlind = $this->dealer->u_place + 1;
            }
            return $smallBlind;
        }
        else {
            return $this->dealer->u_place;
        }
    }

    public function bigBlind() {
        if($this->smallBlind() == $this->arrayPlaces[count($this->arrayPlaces) - 1]) {
            $bigBlind = $this->arrayPlaces[0];
        }
        else {
            $bigBlind = $this->smallBlind() + 1;
        }
        return $bigBlind;
    }

    public function currentBetter() {
        if($this->bigBlind() == $this->arrayPlaces[count($this->arrayPlaces) - 1]) {
        $currentBetter = $this->arrayPlaces[0];
    }
    else {
            $currentBetter = $this->bigBlind() + 1;
        }
        return $currentBetter;
    }

}
