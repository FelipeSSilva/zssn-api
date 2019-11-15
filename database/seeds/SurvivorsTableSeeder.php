<?php

use App\Survivor;
use Illuminate\Database\Seeder;

class SurvivorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Survivor::create([
            'name' => 'Mauricio',
            'age' => 25,
            'gender' => 'Male',
            'latitude' => 2931.21,
            'longitude' => 2132.31
        ]);

        Survivor::create([
            'name' => 'Robson',
            'age' => 23,
            'gender' => 'Male',
            'latitude' => 2932.21,
            'longitude' => 2131.31
        ]);
    }
}
