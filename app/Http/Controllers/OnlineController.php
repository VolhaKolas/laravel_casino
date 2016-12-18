<?php

namespace App\Http\Controllers;

use App\Online;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OnlineRequest;
use Illuminate\Http\Request;

class OnlineController extends Controller
{

    public function online(OnlineRequest $request)
    {

        $data = $request->all();
        $lastV = $data['time'];
        $online = $data['online'];
        $id = auth()->id();

        DB::table('users')->where('id', $id)->update([
            'time' => $lastV,
            'online' => $online
        ]);

    }

}
