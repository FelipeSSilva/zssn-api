<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survivor extends Model
{
    protected $fillable = ['name', 'age', 'gender', 'infected', 'latitude', 'longitude'];


    function resources() {
        return $this->hasMany('App\Resource');
    }

    function infectionReports(){
        return $this->hasMany('App\InfectionReport','survivor_infected_id');
    }

    protected $appends = ['infectionReportsCount'];

    public function getInfectionReportsCountAttribute() {
        $rows = $this->infectionReports();
        return $rows->count();
    }
}
