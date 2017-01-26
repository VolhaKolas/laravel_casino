<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_card extends Model
{
    protected $fillable = [
        'user_id', 'card', 'user_place', 'dealer', 'current_bet'
    ];
    public $timestamps = false;

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
