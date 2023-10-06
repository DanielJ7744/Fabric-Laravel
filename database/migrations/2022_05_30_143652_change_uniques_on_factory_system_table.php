<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUniquesOnFactorySystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('factory_systems', function (Blueprint $table) {
            $table->unique(['factory_id', 'system_id', 'entity_id', 'direction', 'integration_id'], 'factory_system_direction_integration_unique');
            $table->dropUnique('factory_system_direction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factory_systems', function (Blueprint $table) {
            $table->unique(['factory_id', 'system_id', 'direction'], 'factory_system_direction');
            $table->dropUnique(['factory_id', 'system_id', 'entity_id', 'direction', 'integration_id']);
        });
    }
}
