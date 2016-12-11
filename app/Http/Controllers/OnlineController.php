<?php

namespace App\Http\Controllers;

use App\Online;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OnlineRequest;
use Illuminate\Http\Request;

class OnlineController extends Controller
{

    public function online(Request $request) {

        $data = $request->all();
        $lastV = $data['online'];
        $lastV = round($lastV/1000);
        $id = auth()->id();
        $tableId = DB::table('time')->where('user_id', $id)->value('user_id');

        $isId = false;
            if($tableId != null) {
                $isId = true;
            }

        if($isId == false) {
            DB::table('time')->insert([
                'user_id' => $id,
                'time' => $lastV
            ]);
        }
        else {
            DB::table('time')->where('user_id', $id)->update([
                'time' => $lastV
            ]);
        }
        return $lastV;
    }

}
