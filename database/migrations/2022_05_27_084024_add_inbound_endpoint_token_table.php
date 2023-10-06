<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInboundEndpointTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbound_endpoint_token', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inbound_endpoint_id');
            $table->unsignedBigInteger('personal_access_token_id');
            $table->string('display_token');
            $table->timestamps();

            $table
                ->foreign('inbound_endpoint_id')
                ->references('id')->on('inbound_endpoints')
                ->onDelete('cascade');

            $table
                ->foreign('personal_access_token_id')
                ->references('id')->on('personal_access_tokens')
                ->onDelete('cascade');

            $table->index(['inbound_endpoint_id', 'personal_access_token_id'], 'endpoint_token_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inbound_endpoint_token');
    }
}
