<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectorOauthClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connector_oauth_client', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('connector_id');
            $table->uuid('oauth_client_id');
            $table->string('safe_secret');
            $table->timestamps();

            $table
                ->foreign('oauth_client_id')
                ->references('id')
                ->on('oauth_clients')
                ->onDelete('cascade');

            $table->unique(['connector_id', 'oauth_client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connector_oauth_client');
    }
}
