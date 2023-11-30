<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->integer('sent_to_user')->default(null)->nullable()->unsigned()->index();
            $table->foreign('sent_to_user')->references('id')->on('users');
            $table->integer('tournament_id')->unsigned()->index();
            $table->foreign('tournament_id')->references('id')->on('tournaments');

            $table->enum('status', ['queued', 'sent', 'delivered', 'read'])->default('queued');
            //$table->foreign('sent_from')->references('id')->on('users');
            $table->datetime('sent_at')->default(null)->nullable();
            $table->datetime('received_at')->default(null)->nullable();
            $table->text('content', 100);
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
