<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityAndJobTitleAndZipcodeColumsToPeopleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->string('city')->nullable()->default(NULL)->after('gender');
            $table->string('job_title')->nullable()->default(NULL)->after('city');
            $table->string('zipcode')->nullable()->default(NULL)->after('job_title');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::disableForeignKeyConstraints();
        Schema::table('people', function (Blueprint $table) {	
            $table->dropColumn(['city', 'job_title', 'zipcode']);
        });
		Schema::enableForeignKeyConstraints();
    }
}
