<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientInboundEndpointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_inbound_endpoint', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inbound_endpoint_id');
            $table->uuid('client_id');
            $table->string('safe_secret');

            $table
                ->foreign('inbound_endpoint_id')
                ->references('id')
                ->on('inbound_endpoints')
                ->onDelete('cascade');
            $table
                ->foreign('client_id')
                ->references('id')
                ->on('oauth_clients')
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
        Schema::dropIfExists('client_inbound_endpoint');
    }
}
