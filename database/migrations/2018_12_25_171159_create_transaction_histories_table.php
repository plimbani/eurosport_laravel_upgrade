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
            $table->string('order_id')->nullable()->default(NULL);
            $table->string('transaction_key')->comment = "Transaction id from payment response";
            $table->integer('team_size');
            $table->double('amount', 15, 2);
            $table->tinyInteger('status');
            $table->string('card_type')->nullable()->default(NULL);
            $table->string('card_holder_name')->nullable()->default(NULL);
            $table->string('card_number')->nullable()->default(NULL);
            $table->integer('card_validity')->nullable()->default(NULL);
            $table->dateTime('transaction_date')->nullable()->default(NULL);
            $table->string('brand')->nullable()->default(NULL);
            $table->string('currency')->nullable()->default(NULL);
            $table->text('payment_response');
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
