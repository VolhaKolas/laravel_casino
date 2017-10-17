<?php

namespace Casino;

use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    protected $primaryKey = 'u_id';

    public function user() {
        return $this->belongsTo('Casino\User', 'u_id', 'id');
    }
}
