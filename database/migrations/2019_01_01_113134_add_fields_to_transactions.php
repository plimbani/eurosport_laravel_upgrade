<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToTransactions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('transactions', function($table) {
//            $table->string('card_type')->nullable()->default(NULL)->after('status');
//            $table->string('card_holder_name')->nullable()->default(NULL)->after('card_type');
//            $table->string('card_number')->nullable()->default(NULL)->after('card_holder_name');
//            $table->integer('card_validity')->nullable()->default(NULL)->after('card_number');
//            $table->dateTime('transaction_date')->nullable()->default(NULL)->after('card_validity');
//            $table->string('brand')->nullable()->default(NULL)->after('transaction_date');
//            $table->string('currency')->nullable()->default(NULL)->after('brand');            
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('transactions', function($table) {
//            $table->dropColumn(['card_type', 'card_holder_name', 'card_number', 'card_validity', 'transaction_date', 'brand', 'currency']);
//        });
    }
}
