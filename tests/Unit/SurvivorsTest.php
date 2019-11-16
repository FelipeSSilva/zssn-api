<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SurvivorsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCanConnectApi()
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }

    public function testCanGetSurvivorsList()
    {
        $response = $this->get('/api/survivors');

        $response->assertStatus(200);
    }

    public function testCanCreateSurvivor(){
        $data = [
            'name' => 'Name Test',
            'age' => 33,
            'gender' => 'Male',
            'latitude' => 1122.33,
            'longitude' => 5566.77,
        ];

        $this->post(route('survivors.store'), $data)
            ->assertStatus(201)
            ->assertJson($data);
    }

    public function testCanUpdateSurvivor(){
        $data = [
            'latitude' => 8877.66,
            'longitude' => 5544.33,
        ];

        $this->put(route('survivors.update',1), $data)
            ->assertStatus(200)
            ->assertJson($data);
    }

    public function testCanReportInfection(){
        $data = [
            'latitude' => 8877.66,
            'longitude' => 5544.33,
        ];

        $this->post(route('survivors.reportInfection',['survivor_reporter_id'=> 2,'survivor_infected_id'=>1]), $data)
            ->assertStatus(201);

    }
}
