<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChoiceRequest;
use App\Table_card;
use App\Table_user;
use App\User_card;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class ChoiceController extends Controller
{

    private static function nextBetter() {
        $table_id = auth()->user()->tableUsers->table_id;//current table
        $players = Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players
        $userPlace = User_card::where('user_id', auth()->id())->value('user_place');
        $smallBlind = \App\Classes\Position\Blinds::blinds()[1];
        $bigBlind = \App\Classes\Position\Blinds::blinds()[2];

        /*
         * Here we determine who will be the next better. We must consider that there are players who may fold his cards
         */

        $allAvailablePlaces = [];
        foreach ($players as $p) {
            $currentUserPlace = User_card::where('user_id', $p)->value('user_place');
            if(User_card::where('user_id', $p)->value('card') != null) {
                $allAvailablePlaces = array_merge($allAvailablePlaces, [$currentUserPlace]);
            }

            if($currentUserPlace == $smallBlind) {
                $smallBlindId = $p;
            }
            if($currentUserPlace == $bigBlind) {
                $bigBlindId = $p;
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


        if(!isset($nextPlace)) {
            if(User_card::where('user_id', $smallBlindId)->value('card') != null) {
                $nextPlace = $smallBlind;
            }
            else if (User_card::where('user_id', $bigBlindId)->value('card') != null) {
                $nextPlace = $bigBlind;
            }
            else {
                $nextPlace = $allAvailablePlaces[0];
            }
        }


        return $nextPlace;
    }


    public function choice(ChoiceRequest $request) {

        $data = $request->input('answer'); //value of current user bet
        $table_id = auth()->user()->tableUsers->table_id;//current table
        $players = Table_user::where('table_id', $table_id)->pluck("user_id"); //all game players
        $maxBet = Table_user::where('table_id', $table_id)->max('bet');

        if(User_card::where('user_id', auth()->id())->value('current_bet') == 1) { // this 'if' have done for insurance. Only person with current_bet = 1 can make a bet

            $nextPlace = self::nextBetter();

            //we must change current better, appointment of new better is on the bottom of method
            User_card::where('user_id', auth()->id())->update([
                "current_bet" => 0
            ]);

            //here we change columns with money
            if ($data > 0) {
                Table_user::where('user_id', auth()->id())->decrement('money', $data);
                Table_user::where('user_id', auth()->id())->increment('bet', $data);
                Table_card::where('table_id', $table_id)->increment('table_money', $data);

                //if user raise bet he become last_better
                if ($maxBet < Table_user::where('user_id', auth()->id())->value('bet')) {
                    foreach ($players as $player) {
                        User_card::where('user_id', $player)->update([
                            'last_bet' => 0
                        ]);
                    }

                    User_card::where('user_id', auth()->id())->update([
                        'last_bet' => 1
                    ]);
                }
            } //if $data = 0 it's mean user fold cards but only if user isn't first better and don't increase bet (it's mean he check)
            else if ($maxBet != Table_user::where('user_id', auth()->id())->value('bet')) {
                Table_user::where('user_id', auth()->id())->update(['bet' => 0]);
                User_card::where('user_id', auth()->id())->update([
                    'card' => null
                ]);
            }

            $countCards = 0;
            foreach ($players as $player) {
                if(User_card::where('user_id', $player)->value('card') != null) {
                    $countCards++;
                    $winerUser = $player;
                }
            }
            if ($countCards == 1) {
                Table_card::where('table_id', $table_id)->update([
                   'open' => 5
                ]);
                $tableMoney = Table_card::where('table_id', $table_id)->value('table_money');
                Table_card::where('table_id', $table_id)->decrement('table_money', $tableMoney);
                Table_user::where('user_id', $winerUser)->increment('money', $tableMoney);
            }


            $maxBet = Table_user::where('table_id', $table_id)->max('bet');

            $users = [];
            foreach ($players as $player) {
                if (User_card::where('user_id', $player)->value('card') != null) {
                    $users = array_merge($users, [$player]);
                }
                if (User_card::where('user_id', $player)->value('last_bet') == 1) {
                    $lastBeter = $player;
                }
            }

            foreach ($users as $user) {
                if (Table_user::where('user_id', $user)->value("bet") < $maxBet) {
                    $minBet = Table_user::where('user_id', $user)->value("bet");
                }
            }
            if (!isset($minBet)) {
                $minBet = Table_user::where('table_id', $table_id)->max('bet');
            }


                /*
                 * if user's bets are equal and current better is who has bigBlind on preflop and smallBlind on flop, turn, river or who increase the bet
                 * We open river, turn, flop cards
                 */


            if ($maxBet == $minBet and $lastBeter == auth()->id()) {
                $open = Table_card::where('table_id', $table_id)->value('open');
                if ($open == 3) {

                        //final calculations
                    Table_card::where('table_id', $table_id)->update([
                        'open' => 4
                    ]);

                    $priorityArray = [];
                    foreach ($players as $player) {
                        if (User_card::where('user_id', $player)->value('card') != null) {
                            $cards = [];
                            foreach (User_card::where('user_id', $player)->pluck('card') as $card) {
                                $cards = array_merge($cards, [$card]);
                            }
                            $cards = array_merge($cards, [
                                Table_card::where('table_id', $table_id)->value('flop1'),
                                Table_card::where('table_id', $table_id)->value('flop2'),
                                Table_card::where('table_id', $table_id)->value('flop3'),
                                Table_card::where('table_id', $table_id)->value('turn'),
                                Table_card::where('table_id', $table_id)->value('river')
                            ]);
                            $priority = \App\Classes\Calculation\Priority::priority($cards);
                            $priorityArray = array_merge($priorityArray, [
                                ["id" => $player, "priority" => $priority]
                            ]);
                        }
                    }


                    $maxPriority = 0;
                    $winers = [];
                    foreach ($priorityArray as $pa) {
                        if ($pa['priority'] > $maxPriority) {
                            $maxPriority = $pa['priority'];
                        }
                    }

                    foreach ($priorityArray as $pa) {
                        if ($pa['priority'] == $maxPriority) {
                            $winers = array_merge($winers, [$pa['id']]);
                        }
                    }


                    $bank = Table_card::where('table_id', $table_id)->value('table_money');
                    $givenMoney = $bank / count($winers);
                    foreach ($winers as $winer) {
                        Table_user::where('user_id', $winer)->increment('money', $givenMoney);
                    }
                    Table_card::where('table_id', $table_id)->decrement('table_money', $bank);


                }
                else if ($open == 2) {
                    Table_card::where('table_id', $table_id)->update([
                        'open' => 3
                    ]);
                }
                else if ($open == 1) {
                    Table_card::where('table_id', $table_id)->update([
                        'open' => 2
                    ]);
                }
                else if ($open == 0) {
                    Table_card::where('table_id', $table_id)->update([
                        'open' => 1
                    ]);
                }

                foreach ($players as $player) {
                    User_card::where('user_id', $player)->update([
                        'last_bet' => 0
                    ]);

                    if (User_card::where('user_id', $player)->value('dealer') == 2 and User_card::where('user_id', $player)->value('card') != null) {
                        $beterDetermineSB = 1;
                        User_card::where('user_id', $player)->update([
                            'current_bet' => 1
                        ]);
                    }
                }
                if (!isset($beterDetermineSB)) {
                    foreach ($players as $player) {

                        if (User_card::where('user_id', $player)->value('dealer') == 3 and User_card::where('user_id', $player)->value('card') != null) {
                            $beterDetermineBB = 1;
                            User_card::where('user_id', $player)->update([
                                'current_bet' => 1
                            ]);
                        }
                    }
                }
                if (!isset($beterDetermineSB) and !isset($beterDetermineBB)) {
                    foreach ($players as $player) {
                        if (User_card::where('user_id', $player)->value('user_place') == $nextPlace) {
                            User_card::where('user_id', $player)->update([
                                'current_bet' => 1
                            ]);
                        }
                    }
                }

                foreach ($players as $player) {
                    if (User_card::where('user_id', $player)->value('current_bet') == 1) {
                        $currentBetterPlace = User_card::where('user_id', $player)->value('user_place');
                    }
                }

                $allAvailablePlaces = [];
                foreach ($players as $p) {
                    $currentUserPlace = User_card::where('user_id', $p)->value('user_place');
                    if (User_card::where('user_id', $p)->value('card') != null) {
                        $allAvailablePlaces = array_merge($allAvailablePlaces, [$currentUserPlace]);
                    }
                }

                sort($allAvailablePlaces);

                for ($k = 0; $k < count($allAvailablePlaces); $k++) {
                    if ($allAvailablePlaces[$k] == $currentBetterPlace) {
                        if ($k != 0) {
                            $lastBeterPlace = $allAvailablePlaces[$k - 1];
                        } else {
                            $lastBeterPlace = $allAvailablePlaces[count($allAvailablePlaces) - 1];
                        }
                    }
                }

                foreach ($players as $player) {
                    if (User_card::where('user_id', $player)->value('user_place') == $lastBeterPlace) {
                        User_card::where('user_id', $player)->update([
                            'last_bet' => 1
                        ]);
                    }
                }
            } else {
                foreach ($players as $player) {
                    if (User_card::where('user_id', $player)->value('user_place') == $nextPlace) {
                        User_card::where('user_id', $player)->update([
                            'current_bet' => 1
                        ]);
                    }
                }
            }
            if (isset($countCards)) {
                return $countCards;
            }
        }
    }
}
