<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesRecepientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_id')->unsigned()->index();
            $table->foreign('message_id')->references('id')->on('messages');
            $table->integer('user_id')->default(NULL)->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('sid',40)->default(NULL)->nullable();
            $table->string('name',100)->default(NULL)->nullable();
            $table->string('mobile',12)->default(NULL)->nullable();
            $table->string('status',20)->default(NULL)->nullable();
            $table->text('error_json');
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
        Schema::drop('message_recipients');
    }
}
