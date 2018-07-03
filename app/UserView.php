<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserView extends Model
{
    protected $table = 'users_view';

    public function user(){
        return $this->hasOne('App\User');
    }
}
