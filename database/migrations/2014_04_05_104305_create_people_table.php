4<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name',60);
            $table->string('last_name',60);
            $table->string('display_name',50);
            $table->text('address');
            $table->string('dob');
            $table->text('bio');
            $table->string('avatar');
            $table->enum('gender', array('m', 'f', 'n'))->default('n');
            $table->string('primary_email',100);
            $table->string('secondary_email',100);
            $table->string('home_phone',50);
            $table->string('work_phone',50);
            $table->string('mobile_number',30);
            $table->text('v_card');
            $table->string('extra_info');
            $table->string('settings');
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
        Schema::dropIfExists('people');
    }
}
