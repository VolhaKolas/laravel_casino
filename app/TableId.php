<?php

namespace Casino;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class TableId extends Model
{
    public static function set() {
        $tIds = DB::table('tables')->pluck('t_id');
        if(0 == count($tIds)) {
            DB::table('tables')->insert(['t_id' => 1]);
        }
        $currentTId = DB::table('tables')->max('t_id') + 1;
        for ($i = 1; $i < count($tIds); $i++) {
            if ($tIds[$i] - $tIds[$i - 1] > 1) {
                $currentTId = $tIds[$i - 1] + 1;
                break;
            }
        }
        return $currentTId; //returns t_id of new table
    }
}
