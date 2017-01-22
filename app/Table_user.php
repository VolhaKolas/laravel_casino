<?php

namespace App;

use App\Table;
use App\Table_card;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Table_user extends Model
{
    protected $fillable = [
        'table_id', 'user_id', 'money'
    ];
    public $timestamps = false;

    public function tables() {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tableCards() {
        return $this->belongsTo(Table_card::class, 'table_id', 'table_id');
    }


}
