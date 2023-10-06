<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateServiceTemplatesAddIntegrationIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('integration_id')->nullable()->after('destination_factory_system_id');

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
        Schema::table('service_templates', function (Blueprint $table) {
            $table->dropForeign(['integration_id']);
            $table->dropColumn('integration_id');
        });
    }
}
