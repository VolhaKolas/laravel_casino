<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table_user extends Model
{
    protected $fillable = [
        'table_id', 'user_id', 'money', 'timer'
    ];
    public $timestamps = false;

}
