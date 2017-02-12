<?php

namespace App\Http\Controllers;

use App\Table_card;
use App\Table_user;
use App\User_card;
use Illuminate\Http\Request;

class NewDealController extends Controller
{
    public function newDeal()
    {
        if(Table_card::where('table_id', auth()->user()->tableUsers->table_id)->value('open') >= 4)
        {
            $bet = 100;
            $table_id = auth()->user()->tableUsers->table_id;//current table
            $players = Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players

            foreach ($players as $player) {
                Table_user::where('table_id', $table_id)->update([
                    'bet' => 0
                ]);
            }

            foreach ($players as $player) {
                if (User_card::where('user_id', $player)->value('dealer') == 1) {
                    $currentDealer = User_card::where('user_id', $player)->value('user_place');
                }
            }

            if ($currentDealer == count($players)) {
                $dealer = 1;
            } else {
                $dealer = $currentDealer + 1;
            }

            foreach ($players as $player) {
                if (User_card::where('user_id', $player)->value('user_place') == $dealer) {
                    User_card::where('user_id', $player)->update([
                        'dealer' => 1
                    ]);


                }
                else {
                    User_card::where('user_id', $player)->update([
                        'dealer' => 0
                    ]);
                }
                User_card::where('user_id', $player)->update([
                    'current_bet' => 0, 'last_bet' => 0
                ]);
            }

            $smallBlind = \App\Classes\Position\Blinds::blinds()[1];
            $bigBlind = \App\Classes\Position\Blinds::blinds()[2];
            $firstBeter = \App\Classes\Position\Blinds::blinds()[3];

            foreach ($players as $player) {
                $user_place = \App\User_card::where('user_id', $player)->value('user_place');
                if ($user_place == $smallBlind) {
                    Table_user::where('user_id', $player)->decrement('money', $bet / 2);
                    Table_user::where('user_id', $player)->increment('bet', $bet / 2);
                }
                else if ($user_place == $bigBlind) {
                    Table_user::where('user_id', $player)->decrement('money', $bet);
                    Table_user::where('user_id', $player)->increment('bet', $bet);

                    //Add value of last better for bigBlind user, then somebody who increase bet become last better
                    User_card::where('user_id', $player)->update([
                        'last_bet' => 1
                    ]);
                }
                else if ($user_place == $firstBeter) {
                    User_card::where('user_id', $player)->update([
                        'current_bet' => 1
                    ]);
                }

                if (count($players) == 2) {
                    User_card::where('user_id', $player)->update([
                        'current_bet' => 1
                    ]);
                }
            }

            foreach ($players as $player) {
                User_card::where('user_id', $player)->update([
                    'card' => 0
                ]);
            }

            Table_card::where('table_id', $table_id)->update([
                'flop1' => 0, 'flop2' => 0, 'flop3' => 0, 'turn' => 0, 'river' => 0, 'table_money' => $bet + $bet / 2
            ]);


            $card = [];
            for ($i = 0; $i < count($players) * 2 + 5; $i++) {
                $card = array_merge($card, [\App\Classes\CreateArray\CreateArray::create($card)]);
            }

            /*
             * Here we put cards on hands
             */

            for ($j = 0; $j < count($players); $j++) {
                $ids = User_card::where('user_id', $players[$j])->pluck('id');
                $idArray = [];
                foreach ($ids as $id) {
                    $idArray = array_merge($idArray, [$id]);
                }
                User_card::where('id', $idArray[0])->update([
                    'card' => $card[$j]
                ]);
                User_card::where('id', $idArray[1])->update([
                    'card' => $card[count($players) + $j]
                ]);
            }

            /*
                    * Here we put cards on the table table_cards
                    */

            $tableCards = array_slice($card, -5, 5);

            Table_card::where('table_id', $table_id)->update([
                'flop1' => $tableCards[0], 'flop2' => $tableCards[1], 'flop3' => $tableCards[2],
                'turn' => $tableCards[3], 'river' => $tableCards[4], 'open' => 0, 'open' => 0, 'open' => 0
            ]);
        }

    }

}
