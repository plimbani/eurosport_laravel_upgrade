<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->integer('website_id')->unsigned()->index();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('cascade');            
            $table->string('name');
            $table->string('title');
            $table->text('content');
            $table->string('is_additional_page');
            $table->boolean('is_enabled');
            $table->boolean('is_published');
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
        Schema::dropIfExists('pages');
    }
}
