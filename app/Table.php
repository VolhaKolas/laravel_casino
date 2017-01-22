<?php

namespace App;

use App\Table_card;
use App\Table_user;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Table extends Model
{
    protected $fillable = [
        'free', 'timer'
    ];
    public $timestamps = false;

    public function tableCards() {
        return $this->hasOne(Table_card::class);
    }

    public function tableUsers() {
        return $this->hasMany(Table_user::class);
    }

}
