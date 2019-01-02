<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldToTransactions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function($table) {
            $table->integer('status')->change()->comment = "0-Invalid or incomplete, 1-Cancelled by customer, 2-Authorisation refused, 5=authorised, 4=stored, 9=accepted, 6=Authorised and cancelled, 7-Payment deleted, 8-Refund, 9 - Payment requested";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
