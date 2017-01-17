<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserPageController extends Controller
{
    public function userpage() {
        $names = User::select('id', 'name', 'surname', 'online', 'time')->where('id', '!=', auth()->id())->get();
        foreach ($names as $name) {
            if((time() - $name->time)/60 > 15) {
                User::where('id', $name->id)->update(['online' => 0]);
            }
        }
        $names = User::select('name', 'surname', 'online', 'time')->where('id', '!=', auth()->id())->get();


        return view('holdem.userpage', compact('names'));
    }
}
