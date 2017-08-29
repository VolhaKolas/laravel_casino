<?php

namespace Casino\Http\Controllers;

use Casino\TableId;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PlayController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function get() {
        $users = User::where('id', '!=', Auth::id())->where('u_offer', '!=', 1)
            ->orderBy("lastname")->get(); //names of checkboxes: checkbox + user_id

        return view('play', compact('users'));
    }


    public function post(Request $request) {
        $arrayCheckboxes = Input::all();
        $arrayIds = []; //here I put ids of users for whom there is an invitation to play
        foreach ($arrayCheckboxes as $key => $arrayCheckbox) {
            if(preg_match('/checkbox(\d+)/', $key, $matches)) { //all checkboxes which user checked
                $arrayIds = array_merge($arrayIds, [(int)$matches[1]]);
            }
        }

        if(count($arrayIds) > 0) {
            $currentTId = TableId::set(); //получение нового номера стола
            $count = DB::table('users')->where('t_id', $currentTId)->count(); //параноидальная проверка
            if ($count != 0) {
                $currentTId = TableId::set();
            }
            DB::table('tables')->insert(['t_id' => $currentTId]);

            $dealerCard = \Casino\User::firstDealerCardCreation(); //card for dealer determine
            DB::table('users')->where('id', Auth::id())
                ->update([
                    "u_place" => 1,
                    'u_offer' => 1,
                    'u_answer' => 1,
                    't_id' => $currentTId,
                    'u_dealer_card' => $dealerCard]); //add user to DB

            foreach ($arrayIds as $arrayId) { //add users to DB for whom current user send offer
                //TODO-проверку, что кто-то другой не пригласил данного пользователя раньше
                DB::table('users')->where('id', $arrayId)
                    ->update(['u_offer' => 1, 't_id' => $currentTId]);
            }

            //добавлю карты для пользователя

        }
        return redirect()->back();
    }
}