<?php

use App\Item;
use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
            'name' => 'Water',
            'points' => 4
        ]);
        Item::create([
            'name' => 'Food',
            'points' => 3
        ]);
        Item::create([
            'name' => 'Medication',
            'points' => 2
        ]);
        Item::create([
            'name' => 'Ammunition',
            'points' => 1
        ]);
    }
}
