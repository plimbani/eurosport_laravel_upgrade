<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryAgeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_age_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('website_id')->unsigned()->index();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('history_year_id')->unsigned()->index();
            $table->foreign('history_year_id')->references('id')->on('history_years')->onDelete('cascade')->onUpdate('cascade');     
            $table->integer('order');
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_age_categories');
    }
}
