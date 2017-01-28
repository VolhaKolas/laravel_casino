<?php

namespace App\Http\Controllers;

use App\Table_user;
use App\User_card;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    public function cards(Request $request) {

        $data = $request->all();

        //this 'if' for creation cards only by one player. We may use another ways for determine this user
        if(Table_user::where('table_id', auth()->user()->tableUsers->table_id)->min('id') == Table_user::where('user_id', auth()->id())->value('id')) {
            $table_id = auth()->user()->tableUsers->table_id;//current table
            $players = Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players
            $numberOfPlayers = count($players);

            /*
             * Here we create array of numbers which then we will put on hands and on the table
             */

            $numberOfCards = 0;
            foreach ($players as $player) {
                $number = \App\User_card::where('user_id', $player)->count();
                $numberOfCards = $numberOfCards + $number;
            }
            $card = [];
            if ($numberOfPlayers == $numberOfCards) {
                for ($i = 0; $i < $numberOfPlayers * 2 + 5; $i++) {
                    $card = array_merge($card, [\App\Classes\CreateArray\CreateArray::create($card)]);
                }

                /*
                 * Here we put cards on hands
                 */

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

                /*
                 * Here we put cards on the table table_cards
                 */

                $tableCards = array_slice($card, -5, 5);

                \App\Table_card::insert([
                    'table_id' => auth()->user()->tableUsers->table_id, 'flop1' => $tableCards[0], 'flop2' => $tableCards[1],
                    'flop3' => $tableCards[2], 'turn' => $tableCards[3], 'river' => $tableCards[4]
                ]);

                /*
                 * Here we take money from users with bigBlind and smallBlind and put this money on the table
                 */

                $bet = 100;

                $smallBlind = \App\Classes\Position\Blinds::blinds()[1];
                $bigBlind = \App\Classes\Position\Blinds::blinds()[2];
                $firstBeter = \App\Classes\Position\Blinds::blinds()[3];

                $users = Table_user::where('table_id', auth()->user()->tableUsers->table_id)->pluck('user_id');

                foreach ($users as $user) {
                    $user_place = \App\User_card::where('user_id', $user)->value('user_place');
                    if ($user_place == $smallBlind) {
                        Table_user::where('user_id', $user)->decrement('money', $bet / 2);
                        Table_user::where('user_id', $user)->increment('bet', $bet / 2);

                        if($numberOfPlayers == 2) {
                            \App\User_card::where('user_id', $user)->update([
                                'current_bet' => 1
                            ]);
                        }
                    } else if ($user_place == $bigBlind) {
                        Table_user::where('user_id', $user)->decrement('money', $bet);
                        Table_user::where('user_id', $user)->increment('bet', $bet);

                        //Add value of last better for bigBlind user, then somebody who increase bet become last better
                        \App\User_card::where('user_id', $user)->update([
                            'last_bet' => 1
                        ]);
                    } else if ($user_place == $firstBeter) {
                        \App\User_card::where('user_id', $user)->update([
                            'current_bet' => 1
                        ]);
                    }
                }

                \App\Table_card::where('table_id', auth()->user()->tableUsers->table_id)->increment('table_money', $bet + $bet / 2);

                return [$smallBlind, $bigBlind];
            }
        }
    }
}
