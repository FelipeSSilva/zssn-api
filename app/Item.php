<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'points'];

    function resources() {
        return $this->hasMany('App\Resource');
    }
}
