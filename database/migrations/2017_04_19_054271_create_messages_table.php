<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sent_from')->unsigned()->index();
            $table->foreign('sent_from')->references('id')->on('users');
            $table->integer('sent_to_user')->default(NULL)->nullable()->unsigned()->index();
            $table->foreign('sent_to_user')->references('id')->on('users');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');

            $table->enum('status',['queued','sent','delivered','read'])->default('queued');
            //$table->foreign('sent_from')->references('id')->on('users');
            $table->datetime('sent_at')->default(NULL)->nullable();
            $table->datetime('received_at')->default(NULL)->nullable();
            $table->text('content',100);
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
        Schema::drop('messages');
    }
}
