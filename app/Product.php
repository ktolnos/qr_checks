<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    public function check()
    {
        return $this->belongsTo('App\Check');
    }

    public function users(){
        return $this->belongsToMany('App\User')->withPivot('portion');
    }

    public function tags(){
        return $this->belongsToMany('App\Tag', 'product_tag', 'product_id', 'tag_name');
    }
}
