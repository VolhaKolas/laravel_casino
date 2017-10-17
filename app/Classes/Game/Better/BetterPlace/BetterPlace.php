<?php
/**
 * Created by PhpStorm.
 * User: olgakolos
 * Date: 13.10.17
 * Time: 4:02
 */
namespace Casino\Classes\Game\Better\BetterPlace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
abstract class BetterPlace
{
    protected $places;
    protected $arrayPlaces = [];
    protected $better;
    protected $prevBetter;

    public function __construct()
    {
        $this->places =  DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->pluck('u_place');

        $this->places();
    }

    protected function places() {
        foreach ($this->places as $place) {
            $this->arrayPlaces = array_merge($this->arrayPlaces, [$place]);
        }
        sort($this->arrayPlaces);
    }

    protected function checkBetter($prevPlace) {
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

    abstract public function better();
}