<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserPageController extends Controller
{
    public function userpage() {
        $names = User::select('name', 'surname', 'online')->where('id', '!=', auth()->id())->get();


        return view('holdem.userpage', compact('names'));
    }
}
