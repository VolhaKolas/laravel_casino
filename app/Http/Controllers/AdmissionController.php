<?php

namespace Casino\Http\Controllers;

use Casino\TableId;
use Casino\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdmissionController extends Controller
{
    public function post() {
        $place = DB::table('users')->where('t_id', function ($query) {
                $query->select("t_id")->from('users')->where('id', Auth::id());
            })->max('u_place') + 1;

        DB::table('users')->where('id', Auth::id())->
        update(["u_answer" => 1, "u_place" => $place]);

        $existCards = DB::table('users')->where('t_id', function ($query) {
            $query->select("t_id")->from('users')->where('id', Auth::id());
        })->pluck('u_dealer_card');
        $arrayCards = [];
        foreach ($existCards as $existCard) {
            $arrayCards = array_merge($arrayCards, [$existCard]);
        }
        $dCard = User::dealerCardCreation($arrayCards);
        User::where('id', Auth::id())->update(['u_dealer_card' => $dCard]);


        $existUserCards = DB::table('user_cards')->join('users', 'users.id', "=", "user_cards.u_id")->
            where('users.t_id', function($query) {
                $query->select('t_id')->from('users')->where('id', Auth::id());
        })->select('user_cards.uc_card')->get();

        $tableCards = DB::table('tables')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->select('t_flop1', 't_flop2', 't_flop3', 't_turn', 't_river')->get()[0];
        $array = [];
        foreach ($existUserCards as $existUserCard) {
            $array = array_merge($array, [$existUserCard->uc_card]);
        }
        $array = array_merge($array, [
            $tableCards->t_flop1,
            $tableCards->t_flop2,
            $tableCards->t_flop3,
            $tableCards->t_turn,
            $tableCards->t_river]);

        $cards = User::cardsCreation($array, 2);
        foreach ($cards as $card) {
            DB::table('user_cards')->insert([
                'uc_id' => null,
                'u_id' => Auth::id(),
                'uc_card' => $card
            ]);
        }
        return redirect()->back();
    }
}
