<?php

namespace Casino\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class AdmissionController extends Controller
{
    public function post() {
        DB::table('users')->where('id', Auth::id())->
        update(["u_answer" => 1]);
        return redirect()->route('play');
    }
}
