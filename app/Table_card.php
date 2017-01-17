<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table_card extends Model
{
    protected $fillable = [
        'table_id', 'table_money', 'flop1', 'flop2', 'flop3', 'turn', 'river'
    ];
    public $timestamps = false;
}
