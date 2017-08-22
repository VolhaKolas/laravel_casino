<?php

namespace Casino\Http\Controllers;

use Casino\TableId;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PrePlayController extends Controller
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
        $client = new \SplObjectStorage;
        return view('preplay', compact('users'));
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
            DB::table('users')->where('id', Auth::id())
                ->update(['u_offer' => 1, 'u_answer' => 1, 't_id' => $currentTId]);
            foreach ($arrayIds as $arrayId) {
                DB::table('users')->where('id', $arrayId)
                    ->update(['u_offer' => 1, 't_id' => $currentTId]);
            }
            return redirect()->route('play');
        }
        else {
            return redirect()->back();
        }
    }
}
