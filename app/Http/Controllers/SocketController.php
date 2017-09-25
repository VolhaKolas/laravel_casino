<?php

namespace Casino\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class SocketController extends Controller
{
    public function post() {
        $users = DB::table('users')->where('t_id', function ($query) {
            $query->select('t_id')->from('users')->where('id', Auth::id());
        })->where('id', '!=', Auth::id())->select('u_socket', 'id')->get();
        $sockets = [];
        foreach ($users as $user) {
            $string = 'user' . $user->id;
            $sockets = array_merge($sockets, [$string => $user->u_socket]);
        }
        $sockets = array_merge($sockets, ['game' => 1]);
        $sockets = json_encode($sockets);
        return $sockets;
    }

    public function message() {
        $t_id = DB::table('users')->where('id', Auth::id())->pluck('t_id')[0];
        $t_open = DB::table('tables')->where('t_id', $t_id)->pluck('t_open')[0];
        $dealer = DB::table('users')->where('t_id', $t_id)->where('u_dealer', 1)->pluck('id')[0];
        $users = DB::table('users AS u')->join('tables AS t', 'u.t_id', '=', 't.t_id')->where('u.t_id', $t_id)
            ->select('u.id', 'u.login', 'u.u_money', 'u.u_fold', 'u.u_place', 'u.u_photo', 't.t_money',
                't.t_flop1', 't.t_flop2', 't.t_flop3', 't.t_turn', 't.t_river', 't.t_open')->get();
        $currentBetter = DB::table('users')->where('id', Auth::id())->pluck('u_current_better')[0];
        if(1 == $currentBetter) {
            $lastBetter = DB::table('users')->where('id', Auth::id())->pluck('u_last_better')[0];
            $maxBet = DB::table('users')->where('t_id', $t_id)->max('u_bet');
            $userBet = DB::table('users')->where('id', Auth::id())->pluck('u_bet')[0];
            if(1 != $lastBetter and $maxBet != $userBet) {
                $form = 1; //если данный пользователь должен делать ставку, но он непоследний пользователь в раздаче
            }
            else {
                $form = 2; //если данный пользователь должен делать ставку и он последний пользователь в раздаче
            }
        }
        else {
            $form = 3; //если данный пользователь в данный момент не делает ставку
        }
        if(4 == $t_open) {
            $form = 4; //если все розыгрыши разыграли
        }
        $sendData = [];
        foreach ($users as $user) {
            $cards = DB::table('user_cards')->where('u_id', $user->id)->pluck('uc_card');
            $sendData = array_merge($sendData, [[
                'id' => $user->id,
                'card1' => $cards[0],
                'card2' =>$cards[1],
                'login' => $user->login,
                'photo' => $user->u_photo,
                'u_money' =>$user->u_money,
                'fold' => $user->u_fold,
                'dealer' => $dealer,
                'place' => $user->u_place,
                't_money' => $user->t_money,
                'flop1' => $user->t_flop1,
                'flop2' => $user->t_flop2,
                'flop3' => $user->t_flop3,
                'turn' => $user->t_turn,
                'river' => $user->t_river,
                'open' => $user->t_open,
                'form' => $form,
                'user' => Auth::id(),
                'currentBetter' => \Casino\Classes\Game\Players::currentBetter()->login,
                'currentBet' => \Casino\Classes\Game\Players::currentBet(),
                'bet' => \Casino\Classes\Game\Players::BET,
                'checkMoney' => \Casino\Classes\Game\Players::checkMoney()
            ]]);
        }
        $sendData = json_encode($sendData);
        return $sendData;
    }
}
