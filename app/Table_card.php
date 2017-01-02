<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table_card extends Model
{
    protected $fillable = [
        'table_id', 'cards', 'table_money'
    ];
    public $timestamps = false;
}
