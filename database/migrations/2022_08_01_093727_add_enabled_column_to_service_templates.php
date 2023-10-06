<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnabledColumnToServiceTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_templates', function (Blueprint $table) {
            $table->boolean('enabled')->default(true)->after('integration_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_templates', function (Blueprint $table) {
            $table->dropColumn('enabled');
        });
    }
}
