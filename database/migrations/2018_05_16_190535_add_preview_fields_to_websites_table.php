<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreviewFieldsToWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->string('preview_domain_generated_at')->after('domain_name')->nullable()->default(NULL);
            $table->string('preview_domain')->after('domain_name')->nullable()->default(NULL);
            $table->boolean('is_published')->after('is_website_offline')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('preview_domain');
            $table->dropColumn('preview_domain_generated_at');
            $table->dropColumn('is_published');
        });
    }
}
