<?php

namespace App;

use App\Table;
use App\Table_user;
use Illuminate\Database\Eloquent\Model;

class Table_card extends Model
{
    protected $fillable = [
        'table_id', 'table_money', 'flop1', 'flop2', 'flop3', 'turn', 'river'
    ];
    public $timestamps = false;

    public function tables() {
        return $this->belongsTo(Table::class, 'table_id');
    }
    public function tableUsers() {
        return $this->hasMany(Table_user::class);
    }
}
