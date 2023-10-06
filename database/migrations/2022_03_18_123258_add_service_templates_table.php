<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('source_factory_system_id');
            $table->unsignedBigInteger('destination_factory_system_id');
            $table->foreign('source_factory_system_id')->references('id')->on('factory_system');
            $table->foreign('destination_factory_system_id')->references('id')->on('factory_system');
            $table->timestamps();
            $table->unique(['source_factory_system_id', 'destination_factory_system_id'], 'service_template_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_templates');
    }
}
