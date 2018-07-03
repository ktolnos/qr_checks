<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentView extends Model
{
    protected $table = 'payments_view';

    public function payment(){
        return $this->hasOne('App\Payment');
    }
}
