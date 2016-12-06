<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePitchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pitches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('pitch_number')->unsigned();
            $table->enum('type',['grass','artificial']);
            $table->integer('location_id')->unsigned()->index();
            $table->foreign('location_id')->references('id')->on('venues');
            $table->string('availability');
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
        Schema::dropIfExists('pitches');
    }
}
