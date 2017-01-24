<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CardsController extends Controller
{
    public function cards(Request $request) {
        $data = $request->all();
        $table_id = auth()->user()->tableUsers->table_id;//current table
        $players = \App\Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players
        $numberOfPlayers = count($players);

        $numberOfCards = 0;
        foreach ($players as $player) {
            $number = \App\User_card::where('user_id', $player)->count();
            $numberOfCards = $numberOfCards + $number;
        }
        $card = [];
        if($numberOfPlayers == $numberOfCards) {
            for($i = 0; $i < $numberOfPlayers * 2 + 5; $i++) {
                $card = array_merge($card, [\App\Classes\CreateArray\CreateArray::create($card)]);
            }

            for ($j = 0; $j < count($players); $j++) {
                \App\User_card::where('user_id', $players[$j])->update([
                    'card' => $card[$j]
                ]);
                \App\User_card::where('user_id', $players[$j])->insert([
                    'user_id' => $players[$j], 'card' => $card[count($players) + $j]
                ]);
                \App\User_card::where('user_id', $players[$j])->whereNull('user_place')->update([
                    'user_place' => \App\User_card::where('user_id', $players[$j])->whereNotNull('user_place')->value('user_place'),
                    'dealer' => \App\User_card::where('user_id', $players[$j])->whereNotNull('user_place')->value('dealer')
                ]);
            }

            $tableCards = array_slice($card, -5, 5);

            \App\Table_card::insert([
                'table_id' => auth()->user()->tableUsers->table_id, 'flop1' => $tableCards[0], 'flop2' => $tableCards[1],
                'flop3' => $tableCards[2], 'turn' => $tableCards[3], 'river' => $tableCards[4]
            ]);

            $bet = 100;

            $smallBlind = \App\Classes\Position\Blinds::blinds()[1];
            $bigBlind = \App\Classes\Position\Blinds::blinds()[2];
            $firstBeter = \App\Classes\Position\Blinds::blinds()[3];

            \App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->where('user_id', \App\User_card::where('user_place', $smallBlind)->value('user_id'))->decrement('money', $bet/2);
            \App\Table_user::where('table_id', auth()->user()->tableUsers->table_id)->where('user_id', \App\User_card::where('user_place', $bigBlind)->value('user_id'))->decrement('money', $bet);

            \App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->increment('table_money', $bet + $bet/2);

            return [$smallBlind, $bigBlind, $firstBeter];
        }
    }
}
