<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Online extends Model
{
    protected $fillable = [
        'user_id', 'time',
    ];
}
