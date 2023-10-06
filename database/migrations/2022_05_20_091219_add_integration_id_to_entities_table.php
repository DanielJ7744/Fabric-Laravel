<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntegrationIdToEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropUnique(['name']);

            $table->unsignedBigInteger('integration_id')->nullable()->after('name');

            $table->foreign('integration_id')
                ->references('id')
                ->on('integrations')
                ->onDelete('cascade');

            $table->unique(['name', 'integration_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropUnique(['name', 'integration_id']);
            $table->dropForeign(['integration_id']);
            $table->dropColumn('integration_id');
            $table->unique(['name']);
        });
    }
}
