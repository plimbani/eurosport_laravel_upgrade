<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->increments('id')->unsigned(10);
            $table->integer('transaction_id')->unsigned()->index();
            $table->foreign('transaction_id')->references('id')->on('transactions');
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
        Schema::dropIfExists('transaction_histories');
    }
}
