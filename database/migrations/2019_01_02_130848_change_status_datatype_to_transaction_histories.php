<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusDatatypeToTransactionHistories extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("ALTER TABLE transaction_histories MODIFY status ENUM('invalid','cancelled','authorisation_refused','order_stored','authorised','payment_deleted','refund','payment_requested') NOT NULL");
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
