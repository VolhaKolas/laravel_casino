<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_card extends Model
{
    protected $fillable = [
        'user_id', 'card', 'user_place'
    ];
    public $timestamps = false;
}
