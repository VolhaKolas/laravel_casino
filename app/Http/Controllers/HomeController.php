<?php

namespace Casino\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
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
    public function index()
    {
        $firstTableExist = DB::table("tables")->where('t_id', 1)->count(); //добавляю стол с номером 1(стол, где сидят не играющие пользователи)
        if(0 == $firstTableExist) {
            DB::table("tables")->insert(['t_id' => 1]);
        }
        return view('home');
    }
}
