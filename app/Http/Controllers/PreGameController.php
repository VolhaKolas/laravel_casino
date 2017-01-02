<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreGameController extends Controller
{
    public function pregame(Request $request) {
        $table_id = null;
        $user = auth()->id();
        //checking the existence of the user in table_users
        $usersCount = \App\Table_user::select('id')->where('user_id', "$user")->get();

        //if user doesn't exist in the table_users
        if (count($usersCount) == 0) {

            //trying to get id of free table (table with waiting for a game playes or without players)
            $tables = \App\Table::pluck('free', 'id');
            foreach ($tables as $ti => $f) {
                if ($f == 1) {
                    $table_id = $ti;
                    break;
                }
            }

            //if there is not any free table, we create the table
            if (count($tables) == 0 or $table_id == null) {
                \App\Table::insert(
                    ['id' => null, 'free' => 1, 'timer' => time()]
                );

                //and then get id of created table
                $tables = \App\Table::pluck('free', 'id');
                foreach ($tables as $ti => $f) {
                    if ($f == 1) {
                        $table_id = $ti;
                        break;
                    }
                }
            }


            //if the current user is the 8-th player in table with free id, we close this table by free == 0
            $tablesCount = \App\Table_user::where('table_id', "$table_id")->select("user_id")->get();

            if (count($tablesCount) == 7) {
                \App\Table::where('id', "$table_id")->update(['free' => 0]);
            }

            //here we add current user to table_users
            \App\Table_user::insert(
                ['id' => null, 'table_id' => "$table_id", 'user_id' => "$user", 'money' => 1000]
            );
            return view('holdem.pregame', compact('table_id'));
        }
        else {
            $table_id = \App\Table_user::where('user_id', "$user")->value('table_id');
            return view('holdem.pregame', compact('table_id'));
        }
    }

    public function before(Request $request) {
        $table_id =  $request->all();
        $table_id = $table_id['table'];
        $time = \App\Table::where('id', "$table_id")->value('timer');
        $users = \App\Table_user::where('table_id', "$table_id")->select("user_id")->get();
        $countUsers = count($users);
        $timeBefore = 60 - (time() - $time);

        return [$timeBefore, $countUsers];
    }
}
