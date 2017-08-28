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
        $u_ids = DB::table('users')->leftJoin('tables', 'users.t_id', '=', 'tables.t_id')->
        where('users.u_answer', 0)->where('users.id', '!=', Auth::id())->where('tables.t_id', "!=", 1)->pluck("login", 'id');
        $ids = [];
        foreach ($u_ids as $u_id) {
            $ids = array_merge($ids, [$u_id]);
        }
        return $ids;
    }

    public static function loginToAnswer() {
        $u_ids = DB::table('users')->leftJoin('tables', 'users.t_id', '=', 'tables.t_id')->
        where('users.u_answer', 0)->where('users.id', '!=', Auth::id())->where('tables.t_id', "!=", 1)->pluck('login');
        $ids = [];
        foreach ($u_ids as $u_id) {
            $ids = array_merge($ids, [$u_id]);
        }
        return $ids;
    }

    public static function loginIdToAnswer() {
        $u_ids = DB::table('users')->leftJoin('tables', 'users.t_id', '=', 'tables.t_id')->
        where('users.u_answer', 0)->where('users.id', '!=', Auth::id())->where('tables.t_id', "!=", 1)->pluck('login', "id");
        return $u_ids;
    }

}
