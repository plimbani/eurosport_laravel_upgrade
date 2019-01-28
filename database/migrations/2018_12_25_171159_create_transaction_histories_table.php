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
            $table->integer('tournament_id')->unsigned()->index()->nullable()->default(NULL);
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->string('order_id')->nullable()->default(NULL);
            $table->string('transaction_key')->comment = "Transaction id from payment response";
            $table->double('amount', 15, 8);
            $table->tinyInteger('status');
            $table->string('card_type')->nullable()->default(NULL)->after('status');
            $table->string('card_holder_name')->nullable()->default(NULL)->after('card_type');
            $table->string('card_number')->nullable()->default(NULL)->after('card_holder_name');
            $table->integer('card_validity')->nullable()->default(NULL)->after('card_number');
            $table->dateTime('transaction_date')->nullable()->default(NULL)->after('card_validity');
            $table->string('brand')->nullable()->default(NULL)->after('transaction_date');
            $table->string('currency')->nullable()->default(NULL)->after('brand');
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
