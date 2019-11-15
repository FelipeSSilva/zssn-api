<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survivor extends Model
{
    protected $fillable = ['name', 'age', 'gender', 'infected', 'latitude', 'longitude'];


    function resources() {
        return $this->hasMany('App\Resource');
    }

    function infectedReports(){
        return $this->hasMany('App\InfectedReport','survivor_infected_id');
    }

    protected $appends = ['infectedReportsCount'];

    public function getInfectedReportsCountAttribute() {
        $rows = $this->infectedReports();
        return $rows->count();
    }
}
