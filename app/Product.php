<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    public function check()
    {
        return $this->belongsTo('App\Check', 'foreign_key', 'checkId');
    }
}
