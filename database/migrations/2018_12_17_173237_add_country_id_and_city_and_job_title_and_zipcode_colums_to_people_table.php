<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryIdAndCityAndJobTitleAndZipcodeColumsToPeopleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->integer('country_id')->nullable()->default(NULL)->after('gender');
            $table->string('city')->nullable()->default(NULL)->after('country_id');
            $table->string('job_title')->nullable()->default(NULL)->after('city');
            $table->integer('zipcode')->nullable()->default(NULL)->after('job_title');

            $table->foreign('country_id')->references('id')->on('countries');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn(['country_id', 'city', 'job_title', 'zipcode']);
        });
    }
}
