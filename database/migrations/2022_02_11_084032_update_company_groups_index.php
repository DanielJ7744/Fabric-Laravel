<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class UpdateCompanyGroupsIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_groups', function (Blueprint $table) {
            $table->unique(['company_id', 'group_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_groups', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'group_name']);
        });
    }
}
