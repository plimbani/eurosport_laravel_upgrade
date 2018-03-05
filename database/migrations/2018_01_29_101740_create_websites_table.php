<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tournament_name');
            $table->string('tournament_dates');
            $table->string('tournament_location');
            $table->string('domain_name')->nullable();
            $table->integer('linked_tournament')->unsigned()->nullable();
            $table->foreign('linked_tournament')->references('id')->on('tournaments')->onDelete('set null')->onUpdate('cascade');
            $table->string('google_analytics_id')->nullable();
            $table->string('tournament_logo')->nullable();
            $table->string('social_sharing_graphic')->nullable();
            $table->string('color')->nullable();
            $table->string('font')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('websites');
    }
}
