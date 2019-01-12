<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id')->unsigned(10);
            $table->integer('tournament_id')->unsigned()->index()->nullable()->default(NULL);
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('order_id')->unsigned()->index()->nullable()->default(NULL);
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('transaction_key')->comment = "Transaction id from payment response";
            $table->double('amount', 15, 8);
            $table->tinyInteger('status')->comment = "0-failed, 1=success";
            $table->string('payment_response');
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
        Schema::dropIfExists('transactions');
    }
}
