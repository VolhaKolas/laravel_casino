<?php

namespace Casino\Http\Controllers;

use Casino\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomController extends Controller
{
    public function index() {
        return view('welcome');
    }
}
