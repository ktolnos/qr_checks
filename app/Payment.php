<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function view(){
        return $this->hasOne('App\PaymentView');
    }
}
