<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['survivor_id', 'item_id','quantity'];

    function survivor() {
        return $this->belongsTo('App\Survivor');
    }

    function item() {
        return $this->belongsTo('App\Item');
    }
}
