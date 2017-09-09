<?php

namespace Casino;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class TableId extends Model
{
    public static function set() {
        $tIds = DB::table('tables')->pluck('t_id');
        if(0 == count($tIds)) {
            DB::table('tables')->insert(['t_id' => 1]);
        }
        $currentTId = DB::table('tables')->max('t_id') + 1;
        for ($i = 1; $i < count($tIds); $i++) {
            if ($tIds[$i] - $tIds[$i - 1] > 1) {
                $currentTId = $tIds[$i - 1] + 1;
                break;
            }
        }
        return $currentTId; //returns t_id of new table
    }

    public static function answer($user_id) {
        $tId = DB::table('users')->where('id',  $user_id)->pluck('t_id');
        $tId = $tId[0];
        $usersCount =  DB::table('users')->where('t_id', $tId)->count();
        $u_players = DB::table('users')->where('t_id', $tId)->pluck("u_players", "id");

        DB::table('users')->where('id', $user_id)->
        update(['t_id' => 1, 'u_offer' => 0, "u_answer" => 0, "u_dealer_card" => null,
        "u_place" => null, "u_players" => null]);

        if($u_players != null and $u_players != []) {
            foreach ($u_players as $id => $u_player) {
                if ($id != $user_id) {
                    $u_player = json_decode($u_player, true);
                    $newPlayer = [];
                    if($u_player != null and $u_player != []) {
                        foreach ($u_player as $player) {
                            if (gettype($player) == "integer" and $player != $user_id) {
                                $newPlayer = array_merge($newPlayer, [$player]);
                            }
                        }
                        $newPlayer = json_encode($newPlayer);
                        DB::table('users')->where('id', $id)->update(['u_players' => $newPlayer]);
                    }
                }
            }
        }

        if(2 == $usersCount and $tId != 1) {
            DB::table('users')->where('t_id', $tId)->
            update(['t_id' => 1, 'u_offer' => 0, "u_answer" => 0, "u_dealer_card" => null,
                "u_place" => null, "u_players" => null]);
            if(DB::table("tables")->where('t_id', $tId)->count() > 0) {
                DB::table("tables")->where('t_id', $tId)->delete();
            }
        }
    }

    public static function id1() {
        $firstTableExist = DB::table("tables")->where('t_id', 1)->count(); //добавляю стол с номером 1(стол, где сидят не играющие пользователи)
        if(0 == $firstTableExist) {
            DB::table("tables")->insert(['t_id' => 1]);
        }
    }
}
