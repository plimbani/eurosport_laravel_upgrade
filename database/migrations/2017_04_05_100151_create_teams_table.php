<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('assigned_group')->nullable();
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('age_group_id')->nullable()->unsigned()->index();
            $table->foreign('age_group_id')->references('id')->on('tournament_competation_template')->onDelete('cascade');
            $table->integer('club_id')->nullable()->unsigned()->index();
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->integer('competation_id')->nullable()->unsigned()->index();
            $table->foreign('competation_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->string('group_name')->nullable();
            $table->string('name')->nullable();
            $table->string('place')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('shirt_color')->nullable();
            $table->string('esr_reference')->nullable();
            $table->integer('country_id')->nullable()->unsigned()->index();
            $table->foreign('country_id')->references('id')->on('countries');
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
        Schema::dropIfExists('teams');
    }
}
