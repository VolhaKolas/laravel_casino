<?php

namespace Casino\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ExitController extends Controller
{
    public function post() {
        /*
        $dealer = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->where('u_dealer', 1)->select('id', 'u_place', 't_id')->get()[0];
        $places = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->pluck('u_place');
        $arrayPlaces = [];
        foreach ($places as $place) {
            $arrayPlaces = array_merge($arrayPlaces, [$place]);
        }
        sort($arrayPlaces);
        if($dealer->id == Auth::id()) {
            if($dealer->u_place == $arrayPlaces[count($arrayPlaces) - 1]) {
                $newDealerPlace = $arrayPlaces[0];
            }
            else {
                $newDealerPlace = $dealer->u_place + 1;
            }
            //TODO-сделать красивый update с нормальным t_id
            DB::table('users')->where('t_id', $dealer->t_id)->
                where('u_place', $newDealerPlace)->update(['u_dealer' => 1]);
        }
        DB::table('user_cards')->where('u_id', Auth::id())->delete();
        DB::table('users')->where('id', Auth::id())->
            update(['u_place' => null, 'u_dealer' => 0, 'u_money' => 1000, 'u_offer' => 0, 'u_answer' => 0, 't_id' => 1]);
        */

        return redirect()->back();
    }
}
