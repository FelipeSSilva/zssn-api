<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurvivorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survivors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('age');
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('infected', ['Y', 'N'])->default('N');
            $table->decimal('latitude',10,2);
            $table->decimal('longitude',10,2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survivors');
    }
}
