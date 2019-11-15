<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfectionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infection_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survivor_reporter_id')->unsigned();
            $table->integer('survivor_infected_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('survivor_reporter_id')->references('id')->on('survivors');
            $table->foreign('survivor_infected_id')->references('id')->on('survivors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infection_reports');
    }
}
