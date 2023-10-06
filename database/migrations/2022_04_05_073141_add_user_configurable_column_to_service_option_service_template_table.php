<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserConfigurableColumnToServiceOptionServiceTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_option_service_template', function (Blueprint $table) {
            $table->boolean('user_configurable')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_option_service_template', function (Blueprint $table) {
            $table->dropColumn('user_configurable');
        });
    }
}
