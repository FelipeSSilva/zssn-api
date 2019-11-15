<?php

use App\Resource;
use Illuminate\Database\Seeder;

class ResourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Resource::create([
            'survivor_id' => 1,
            'item_id' => 2,
            'quantity' => 3,
        ]);

        Resource::create([
            'survivor_id' => 2,
            'item_id' => 1,
            'quantity' => 3,
        ]);
    }
}
