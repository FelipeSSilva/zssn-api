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

    public function checkItemQuantity($itemName){
        $resourceSurvivor = Survivor::select('resources.quantity')
            ->join('resources', 'survivors.id', '=', 'resources.survivor_id')
            ->join('items', 'items.id', '=', 'resources.item_id')
            ->where('survivors.id','=',$this->id)
            ->where('items.name','like','%'.$itemName.'%')
            ->first();

        if(!empty($resourceSurvivor->quantity)){
            return (int) $resourceSurvivor->quantity;
        }else{
            return 0;
        }

    }

    protected $appends = ['infectionReportsCount'];

    public function getInfectionReportsCountAttribute() {
        $rows = $this->infectionReports();
        return $rows->count();
    }
}
