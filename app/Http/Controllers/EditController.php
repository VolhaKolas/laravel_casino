<?php

namespace Casino\Http\Controllers;

use Casino\Http\Requests\EditRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Symfony\Component\Yaml\Tests\A;

class EditController extends Controller
{

    public function get() {
        $user = Auth::user();
        if(Auth::user()->u_photo != null) {
            $filePath = 'photos/' . Auth::id() . "/" . Auth::user()->u_photo;
        }
        else {
            $filePath = "css/image/profile.png";
        }
        return view('edit', compact('user', 'filePath'));
    }

    public function post(EditRequest $request) {
        if($request->hasFile('u_photo')) {
            $file = $request->file('u_photo');
            $fileName = $request->file('u_photo')->getFilename();
            $filePath = public_path() . '/photos/' . Auth::id();
            $file->move($filePath);
            if(Auth::user()->u_photo != null and file_exists($filePath . "/" . Auth::user()->u_photo)) {
                unlink($filePath . "/" . Auth::user()->u_photo);
            }
            DB::table("users")->where("id", Auth::id())
                ->update(['name' => $request->name, "lastname" => $request->lastname, "u_photo" => $fileName]);
        }
        else {
            DB::table("users")->where("id", Auth::id())
                ->update(['name' => $request->name, "lastname" => $request->lastname]);
        }
        return redirect()->route('edit');
    }
}
