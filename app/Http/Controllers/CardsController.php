<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CardsController extends Controller
{
    public function cards(Request $request) {
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
        }

        return array_slice($card, 0, -5);
    }
}
