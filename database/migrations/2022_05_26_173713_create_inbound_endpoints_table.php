<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInboundEndpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbound_endpoints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('integration_id');
            $table->string('service_id')->unique();
            $table->string('slug');
            $table->timestamps();

            $table
                ->foreign('integration_id')
                ->references('id')->on('integrations')
                ->onDelete('cascade');

            $table->index(['integration_id', 'slug']);
            $table->index(['integration_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inbound_endpoints');
    }
}
