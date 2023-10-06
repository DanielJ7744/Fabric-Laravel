<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUniquesOnServiceTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_templates', function (Blueprint $table) {
            $table->unique(['source_factory_system_id', 'destination_factory_system_id', 'integration_id'], 'service_template_integration_id_unique');
            $table->dropUnique('service_template_unique');
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
            $table->unique(['source_factory_system_id', 'destination_factory_system_id'], 'service_template_unique');
            $table->dropUnique('service_template_integration_id_unique');
        });
    }
}
