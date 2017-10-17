<?php

namespace Casino;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $primaryKey = 't_id';

    public function user() {
        return $this->hasMany('Casino\User', 't_id', 't_id');
    }
}
