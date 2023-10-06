<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddCompanyGroupIdColumnToIntegrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->unsignedBigInteger('company_group_id')->nullable();
            $table->foreign('company_group_id')->references('id')->on('company_groups')->onDelete('cascade');
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
            $table->dropForeign(['company_group_id']);
            $table->dropColumn('company_group_id');
        });
    }
}
