<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdjustServiceTemplateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('service_templates', function (Blueprint $table) {
            $table->dropForeign(['source_factory_system_id']);
            $table->dropForeign(['destination_factory_system_id']);

            $table
                ->foreign('source_factory_system_id')
                ->references('id')
                ->on('factory_systems')
                ->onDelete('cascade');
            $table
                ->foreign('destination_factory_system_id')
                ->references('id')
                ->on('factory_systems')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('service_templates', function (Blueprint $table) {
            $table->dropForeign(['source_factory_system_id']);
            $table->dropForeign(['destination_factory_system_id']);

            $table->foreign('source_factory_system_id')->references('id')->on('factory_systems');
            $table->foreign('destination_factory_system_id')->references('id')->on('factory_systems');
        });
    }
}
