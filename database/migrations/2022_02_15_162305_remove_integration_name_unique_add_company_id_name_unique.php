<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIntegrationNameUniqueAddCompanyIdNameUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->unique(['company_id', 'name']);
            $table->dropUnique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'name']);
            $table->unique('name');
        });
    }
}
