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

        $data = $request->input('answer');
        $table_id = auth()->user()->tableUsers->table_id;//current table
        $players = Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players
        $numberOfPlayers = count($players); //count of players

        if(User_card::where('user_id', auth()->id())->value('current_bet') == 1) {
            $userPlace = User_card::where('user_id', auth()->id())->value('user_place');

            User_card::where('user_id', auth()->id())->update([
                "current_bet" => 0
            ]);

            if($data > 0) {
                Table_user::where('user_id', auth()->id())->decrement('money', $data);
                Table_user::where('user_id', auth()->id())->increment('bet', $data);
                Table_card::where('table_id', $table_id)->increment('table_money', $data);
            }

            else if(Table_user::where('table_id', $table_id)->max('bet') != Table_user::where('user_id', auth()->id())->value('bet')) {
                Table_user::where('user_id', auth()->id())->update(['bet' => 0]);
                User_card::where('user_id', auth()->id())->update([
                    'card' => null
                ]);
            }

            $maxBet = Table_user::where('table_id', $table_id)->max('bet');
            $userBet = Table_user::where('user_id', auth()->id())->value('bet');
            $bigBlind = User_card::where('dealer', 3)->value('user_id');

            if($maxBet == $userBet and $bigBlind == auth()->id()) {
                if(Table_card::where('table_id', $table_id)->value('flop_open') == 1 and Table_card::where('table_id', $table_id)->value('turn_open') == 1) {
                    Table_card::where('table_id', $table_id)->update([
                        'river_open' => 1
                    ]);
                }
                else if(Table_card::where('table_id', $table_id)->value('flop_open') == 1) {
                    Table_card::where('table_id', $table_id)->update([
                        'turn_open' => 1
                    ]);
                }
                else {
                    Table_card::where('table_id', $table_id)->update([
                        'flop_open' => 1
                    ]);
                }
            }

            $nextPlace = $userPlace + 1;
            if ($numberOfPlayers - $userPlace == 0) {
                $nextPlace = 1;
            }

            for ($i = 0; $i < $numberOfPlayers; $i++) {
                if(User_card::where('user_id', $players[$i])->whereNull('card')->value('user_place') == $nextPlace) {
                    $userPlace = $userPlace + 1;
                    if ($numberOfPlayers - $userPlace == 0) {
                        $userPlace = 1;
                        $i = 0;
                    }
                }
            }

            $nextPlace = $userPlace + 1;
            if ($numberOfPlayers - $userPlace == 0) {
                $nextPlace = 1;
            }

            foreach ($players as $player) {
                if(User_card::where('user_id', $player)->value('user_place') == $nextPlace) {
                    User_card::where('user_id', $player)->update([
                        'current_bet' => 1
                    ]);
                }
            }

            /*for ($i = 0; $i < count($players); $i++) {
                $currentBeter = User_card::where('user_id', $players[$i])->value('current_bet');
                $userPlace = User_card::where('user_id', $players[$i])->value('user_place');
                User_card::where('user_id', $players[$i])->update([
                    'current_bet' => 0
                ]);
                if ($currentBeter == 1) {
                    $nextPlace = $userPlace + 1;
                    if ($numberOfPlayers - $userPlace == 0) {
                        $nextPlace = 1;
                    }
                }
            }

            $users = [];
            foreach ($players as $player) {
               if(User_card::where('user_id', $player)->value('card') != null) {
                   $users = array_merge($users, [$player]);
               }
            }

            if($numberOfPlayers != count($users)) {
                for ($j = 0; $j < count($users); $j++) {
                    $userPlace = User_card::where('user_id', $users[$j])->value('user_place');
                    if ($userPlace == $nextPlace) {
                        $folder = User_card::where('user_id', $users[$j])->value('card');
                        if ($folder == null) {
                            if (count($users) - $nextPlace == 0) {
                                $nextPlace = 1;
                                $j = 0;
                            } else {
                                $nextPlace = $nextPlace + 1;
                            }
                        } else {
                            User_card::where('user_id', $users[$j])->update([
                                'current_bet' => 1
                            ]);
                        }
                    }
                }
            }*/
        }


        return view('holdem.holdem');
    }
}
