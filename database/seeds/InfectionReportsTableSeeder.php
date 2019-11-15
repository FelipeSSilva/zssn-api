<?php

use App\InfectionReport;
use Illuminate\Database\Seeder;

class InfectionReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InfectionReport::create([
            'survivor_reporter_id' => 1,
            'survivor_infected_id' => 2
        ]);
    }
}
