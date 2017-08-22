<?php

namespace Casino\Http\Controllers;

use Casino\Http\Requests\EditPassRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EditPassController extends Controller
{
    public function get() {
        return view('editpass');
    }

    public function post(EditPassRequest $request) {
        $oldPass = Auth::user()->password;
        if(Hash::check($request->oldpassword, $oldPass)) {
            DB::table('users')->where('id', Auth::id())
                ->update(['password' =>  bcrypt($request->password)]);
        }
        return redirect()->route('editpass');
    }
}
