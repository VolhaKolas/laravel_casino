<?php

namespace Casino\Http\Controllers;

use Casino\Classes\Game\Players;
use Casino\TableId;
use Casino\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class AnswerController extends Controller
{
    public function post(Request $request) { //ajax после получения сокета
        $answer = $request->all()['answer'];
        $user = (int) $request->all()['user'];
        //если сокет сработал раньше, чем юзер, приславший его, внес изменения в БД
        if(0 == $answer) {
            TableId::answer($user);
        }
        else {
            DB::table('users')->where('id', $user)->
            update(["u_answer" => 1]);
        }
        $t_id = User::where('id', Auth::id())->pluck('t_id')[0];
        if(1 == $t_id) { //если все пользователи отменили игру
            return 0;
        }
        else {
            $offer = DB::table('users')->where('t_id', function ($query) {
                $query->select("t_id")->from('users')->where('id', Auth::id());
            })->where('u_offer', 1)->count();
            $ans = DB::table('users')->where('t_id', function ($query) {
                $query->select("t_id")->from('users')->where('id', Auth::id());
            })->where('u_answer', 1)->count();
            if($offer == $ans) { //если все пользователи готовы играть

                $dealerCards = DB::table("users")->where('t_id', function ($query) {
                    $query->select("t_id")->from('users')->where('id', Auth::id());
                })->select('u_dealer_card', "u_place", 'id')->get();

                foreach ($dealerCards as $key => $dealerCard) {
                    if(0 == $key) {
                        $dealCard = $dealerCard->u_dealer_card % 100;//определение дилера
                        $place = $dealerCard->u_place;
                        $dealer = $dealerCard->id;
                    }
                    else {
                        $dealerC = $dealerCard->u_dealer_card % 100;
                        $dealerP = $dealerCard->u_place;
                        $dealerId = $dealerCard->id;
                        if($dealerC > $dealCard or ($dealerC == $dealCard and $dealerP < $place)) {
                            $dealCard = $dealerC;
                            $place = $dealerP;
                            $dealer = $dealerId;
                        }
                    }
                }
                DB::table('users')->where('id', $dealer)->update(['u_dealer'=> 1]);

                $data = DB::table('users')->where('t_id', function($query) {
                    $query->select('t_id')->from('users')->where('id', Auth::id());
                })->select('u_place', 'u_photo', 'u_dealer_card', 'id', 'login', 'u_money')->get();
                $array = [];
                foreach ($data as $item) {
                    $array = array_merge($array, [['place' => $item->u_place, 'id' => $item->id,
                        'photo' => $item->u_photo, 'card' => $item->u_dealer_card,
                        'login' => $item->login, 'money' => $item->u_money,
                        "user" => Auth::id(), "dealer" => Players::dealer()]]); //данные для ajax вывода карт
                }
                $array = json_encode($array);

                return $array;
            }
            else { //если кого-то еще ждем
                return 1;
            }
        }
    }

    public function socket(Request $request) {
        $connectionNumber = $request->all()['connection'];
        User::where('id', Auth::id())->
            update(["u_socket" => $connectionNumber]);
    }

    public function invitation(Request $request) {
        $socketData = [];
        $key = array_keys($request->all()); //this all done because laravel converts json data to a weird format: ({{jsonData}: null})
        $key = $key[0];
        $key = json_decode($key, true);
        foreach ($key as $key1 => $id) {
            if($id != Auth::id()) {
                $id = (int)$id;
                $connection = DB::table("users")->where('id', $id)->pluck('u_socket')[0];
                $socketData = array_merge($socketData, [$connection]);
            }
        }
        $socketData = json_encode($socketData);
        return $socketData;
    }

    public function setinput(Request $request) {
        return User::players();
    }
}
