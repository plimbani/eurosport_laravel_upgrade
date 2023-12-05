<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->timestamp('preview_domain_generated_at')->after('domain_name')->nullable()->default(null);
            $table->string('preview_domain')->after('domain_name')->nullable()->default(null);
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
