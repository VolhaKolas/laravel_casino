<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChoiceRequest;
use App\Table_card;
use App\Table_user;
use App\User_card;
use Illuminate\Http\Request;

class ChoiceController extends Controller
{
    public function choice(ChoiceRequest $request) {

        $data = $request->input('answer'); //value of current user bet
        $table_id = auth()->user()->tableUsers->table_id;//current table
        $players = Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players
        $numberOfPlayers = count($players); //count of players
        $maxBet = Table_user::where('table_id', $table_id)->max('bet');

        if(User_card::where('user_id', auth()->id())->value('current_bet') == 1) { // this 'if' have done for insurance. Only person with current_bet = 1 can make a bet
            $userPlace = User_card::where('user_id', auth()->id())->value('user_place');

            /*
             * Here we determine who will be the next better. We must consider that there are players who may fold his cards
             */

            $allAvailablePlaces = [];
            foreach ($players as $p) {
                $currentUserPlace = User_card::where('user_id', $p)->value('user_place');
                if(User_card::where('user_id', $p)->value('card') != null) {
                    $allAvailablePlaces = array_merge($allAvailablePlaces, [$currentUserPlace]);
                }
            }

            sort($allAvailablePlaces);

            for($i = 0; $i < count($allAvailablePlaces); $i++) {
                if($allAvailablePlaces[$i] == $userPlace) {
                    if($allAvailablePlaces[$i] == $allAvailablePlaces[count($allAvailablePlaces) - 1]) {
                        $nextPlace = $allAvailablePlaces[0];
                    }
                    else {
                        $nextPlace = $allAvailablePlaces[$i + 1];
                    }
                }
            }

            //we must change current better, appointment of new better is on the bottom of method
            User_card::where('user_id', auth()->id())->update([
                "current_bet" => 0
            ]);

            //here we change columns with money
            if($data > 0) {
                Table_user::where('user_id', auth()->id())->decrement('money', $data);
                Table_user::where('user_id', auth()->id())->increment('bet', $data);
                Table_card::where('table_id', $table_id)->increment('table_money', $data);
                if($maxBet < Table_user::where('user_id', auth()->id())->value('bet')) {
                    foreach ($players as $player) {
                        User_card::where('user_id', $player)->update([
                            'last_bet' => 0
                        ]);
                    }

                    User_card::where('user_id', auth()->id())->update([
                        'last_bet' => 1
                    ]);
                }
            }

            //if $data = 0 it's mean user fold cards but only if user isn't first better and don't increase bet
            else if($maxBet != Table_user::where('user_id', auth()->id())->value('bet')) {
                Table_user::where('user_id', auth()->id())->update(['bet' => 0]);
                User_card::where('user_id', auth()->id())->update([
                    'card' => null
                ]);
            }



            $maxBet = Table_user::where('table_id', $table_id)->max('bet');

            $users = [];
            foreach ($players as $player) {
                if(User_card::where('user_id', $player)->value('card') != null) {
                    $users = array_merge($users, [$player]);
                }
                if(User_card::where('user_id', $player)->value('last_bet') == 1) {
                    $lastBeter = $player;
                }
            }

            foreach ($users as $user) {
                if(Table_user::where('user_id', $user)->value("bet") < $maxBet) {
                    $minBet = Table_user::where('user_id', $user)->value("bet");
                }
            }
            if(!isset($minBet)) {
                $minBet = Table_user::where('table_id', $table_id)->max('bet');
            }


            /*
             * if user's bets are equal and current better is who has bigBlind on preflop and smallBlind on flop, turn and river
             * or current better is who increase bet
             * We open river, turn, flop cards
             */


            if($maxBet == $minBet and $lastBeter == auth()->id()) {
                if (Table_card::where('table_id', $table_id)->value('flop_open') == 1 and Table_card::where('table_id', $table_id)->value('turn_open') == 1) {
                    Table_card::where('table_id', $table_id)->update([
                        'river_open' => 1
                    ]);
                }
                else if (Table_card::where('table_id', $table_id)->value('flop_open') == 1) {
                    Table_card::where('table_id', $table_id)->update([
                        'turn_open' => 1
                    ]);
                }
                Table_card::where('table_id', $table_id)->update([
                    'flop_open' => 1
                ]);
                foreach ($players as $player) {
                    User_card::where('user_id', $player)->update([
                        'last_bet' => 0
                    ]);

                    if(User_card::where('user_id', $player)->value('dealer') == 2 and User_card::where('user_id', $player)->value('card') != null) {
                        $beterDetermineSB = 1;
                        User_card::where('user_id', $player)->update([
                            'current_bet' => 1
                        ]);

                        User_card::where('user_id', $player)->update([
                            'last_bet' => 1
                        ]);
                    }
                }
                if(!isset($beterDetermineSB)) {
                    foreach ($players as $player) {

                        if(User_card::where('user_id', $player)->value('dealer') == 3 and User_card::where('user_id', $player)->value('card') != null) {
                            $beterDetermineBB = 1;
                            User_card::where('user_id', $player)->update([
                                'current_bet' => 1
                            ]);

                            User_card::where('user_id', $player)->update([
                                'last_bet' => 1
                            ]);
                        }
                    }
                }
                if(!isset($beterDetermineSB) and !isset($beterDetermineBB)) {
                    foreach ($players as $player) {
                        if(User_card::where('user_id', $player)->value('user_place') == $nextPlace) {
                            User_card::where('user_id', $player)->update([
                                'current_bet' => 1
                            ]);
                        }
                    }
                }
            }
            else {
                foreach ($players as $player) {
                    if(User_card::where('user_id', $player)->value('user_place') == $nextPlace) {
                        User_card::where('user_id', $player)->update([
                            'current_bet' => 1
                        ]);
                    }
                }
            }
        }
        return view('holdem.holdem');
    }
}
