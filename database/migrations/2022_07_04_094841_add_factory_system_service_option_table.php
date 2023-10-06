<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactorySystemServiceOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factory_system_service_option', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('factory_system_id');
            $table->unsignedBigInteger('service_option_id');
            $table->string('value', 3000)->nullable()->default(NULL);
            $table->boolean('user_configurable')->default(false);
            $table->foreign('factory_system_id')->references('id')->on('factory_systems');
            $table->foreign('service_option_id')->references('id')->on('service_options');
            $table->unique(['factory_system_id', 'service_option_id'], 'factory_system_service_option_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factory_system_service_option');
    }
}
