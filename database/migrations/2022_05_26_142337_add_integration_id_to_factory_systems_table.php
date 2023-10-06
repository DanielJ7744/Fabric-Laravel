<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntegrationIdToFactorySystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('factory_systems', function (Blueprint $table) {
            $table->unsignedBigInteger('integration_id')->nullable();

            $table->foreign('integration_id')
                ->references('id')
                ->on('integrations')
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
        Schema::table('factory_systems', function (Blueprint $table) {
            $table->dropForeign(['integration_id']);
            $table->dropColumn('integration_id');
        });
    }
}
