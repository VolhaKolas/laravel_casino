<?php

namespace Casino;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'name', 'lastname', 'email', 'password', 'u_time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function offer() {
        return User::where('id', Auth::id())->pluck('u_offer');
    }

    public static function answer() {
        return User::where('id', Auth::id())->pluck('u_answer');
    }


    public static function usersToAnswer() {
        $u_ids = DB::table('users')
            ->where('t_id', function ($query) {
            $query->select("t_id")->from('users')->where('id', Auth::id());
        })->where('u_answer', 0)->where('id', '!=', Auth::id())->where('t_id', "!=", 1)->pluck("login", 'id');
        $ids = [];
        foreach ($u_ids as $u_id) {
            $ids = array_merge($ids, [$u_id]);
        }
        return $ids;
    }

    public static function loginToAnswer() {
        $u_ids = DB::table('users')
            ->where('t_id', function ($query) {
            $query->select("t_id")->from('users')->where('id', Auth::id());
        })->where('u_answer', 0)->where('id', '!=', Auth::id())->where('t_id', "!=", 1)->pluck('login');
        $ids = [];
        foreach ($u_ids as $u_id) {
            $ids = array_merge($ids, [$u_id]);
        }
        return $ids;
    }

    public static function loginIdToAnswer() {
        $u_ids = DB::table('users')
            ->where('t_id', function ($query) {
            $query->select("t_id")->from('users')->where('id', Auth::id());
        })->where('u_answer', 0)->where('id', '!=', Auth::id())->where('t_id', "!=", 1)->pluck('login', "id");
        return $u_ids;
    }

    public static function gameBegin() {
        $t_id = DB::table('users')->where('id', Auth::id())->pluck('t_id')[0];
        if(1 != $t_id) {
            $countUsers = DB::table('users')->where('t_id', $t_id)->count();
            $answerUsers = DB::table('users')->where('t_id', $t_id)->where('u_answer', 1)->count();
            if($countUsers == $answerUsers) {
                return 1;
            }
            return 0;
        }
        return 0;
    }

    public static function firstDealerCardCreation() {
        $card = mt_rand(2, 14); // создаю случайное число в диапозоне от 2 до 14
        $multiplier = mt_rand(0, 3); //формирую случайным образом множитель
        if (1 == $multiplier) { //если множитель равен 1, добавляю к числу 100
            $card = $card + 100;
        } else if (2 == $multiplier) { //если множитель равен 2, добавляю к числу 200
            $card = $card + 200;
        } else if (3 == $multiplier) { //если множитель равен 3, добавляю к числу 300
            $card = $card + 300;
        }
        return $card;
    }

    public static function dealerCardCreation($array) {
        $card = 0;
        while (0 == $card) {
            $card = mt_rand(2, 14); // создаю случайное число в диапозоне от 2 до 14
            $multiplier = mt_rand(0, 3); //формирую случайным образом множитель
            if (1 == $multiplier) { //если множитель равен 1, добавляю к числу 100
                $card = $card + 100;
            } else if (2 == $multiplier) { //если множитель равен 2, добавляю к числу 200
                $card = $card + 200;
            } else if (3 == $multiplier) { //если множитель равен 3, добавляю к числу 300
                $card = $card + 300;
            }
            if(in_array($card, $array)) {
                $card = 0;
            }
        }
        return $card;
    }

    public static function firstCardsCreation() {
        $arrayCards = [];
        for ($i = 0; $i < 2; $i++) {
            $card = mt_rand(2, 14); // создаю случайное число в диапозоне от 2 до 14
            $multiplier = mt_rand(0, 3); //формирую случайным образом множитель
            if (1 == $multiplier) { //если множитель равен 1, добавляю к числу 100
                $card = $card + 100;
            } else if (2 == $multiplier) { //если множитель равен 2, добавляю к числу 200
                $card = $card + 200;
            } else if (3 == $multiplier) { //если множитель равен 3, добавляю к числу 300
                $card = $card + 300;
            }

            if (!in_array($card, $arrayCards)) { //проверяю, есть ли в массиве такое число, если нет добавляю его в массив
                $arrayCards = array_merge($arrayCards, [$card]);
            } else { // если число есть в массиве, откатываю цикл, чтобы сформировать новое число
                $i--;
            }
        }
        return $arrayCards;
    }

    public static function players() {
        return DB::table("users")->where('id', Auth::id())->pluck('u_players')[0];
    }

}
