<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function($table) {
            $table->string('days')->nullable()->default(NULL)->after('status');
        });
        
        Schema::table('transaction_histories', function($table) {
            $table->string('days')->nullable()->default(NULL)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function($table) {
            $table->dropColumn(['days']);
        });
        
        Schema::table('transaction_histories', function($table) {
            $table->dropColumn(['days']);
        });
    }
}
