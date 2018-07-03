<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckView extends Model
{
    protected $table = 'checks_view';

    public function check(){
        return $this->hasOne('App\Check');
    }
}
