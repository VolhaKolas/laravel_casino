<?php

namespace Casino\Http\Controllers;

use Casino\TableId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class BreakController extends Controller
{
    public function post() {
        TableId::answer(Auth::id());
        return redirect()->back();
    }
}
