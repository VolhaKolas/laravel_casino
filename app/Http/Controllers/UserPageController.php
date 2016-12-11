<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UserPageController extends Controller
{
    public function userpage() {
        return view('holdem.userpage');
    }
}
