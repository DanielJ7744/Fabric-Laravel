<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('integration_id');
            $table->foreign('integration_id')->references('id')->on('integrations')->onDelete('cascade');
            $table->unsignedBigInteger('event_type_id');
            $table->foreign('event_type_id')->references('id')->on('event_types')->onDelete('cascade');
            $table->boolean('active')->default(true);
            $table->unsignedInteger('service_id');
            $table->string('remote_reference');
            $table->timestamps();

            $table->unique(['integration_id', 'service_id', 'event_type_id', 'remote_reference'], 'webhook_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhooks');
    }
}
