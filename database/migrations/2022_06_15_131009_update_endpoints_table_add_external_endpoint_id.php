<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEndpointsTableAddExternalEndpointId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inbound_endpoints', function (Blueprint $table) {
            $table->unsignedBigInteger('external_endpoint_id')->nullable()->after('service_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inbound_endpoints', function (Blueprint $table) {
            $table->dropColumn('external_endpoint_id');
        });
    }
}
