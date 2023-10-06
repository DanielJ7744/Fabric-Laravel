<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceOptionServiceTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_option_service_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_option_id');
            $table->unsignedBigInteger('service_template_id');
            $table->enum('target', ['source', 'destination']);
            $table->foreign('service_option_id')->references('id')->on('service_options');
            $table->foreign('service_template_id')->references('id')->on('service_templates');
            $table->string('value', 3000)->nullable()->default(NULL);
            $table->unique(['service_option_id', 'service_template_id', 'target'], 'service_option_service_template_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_option_service_template');
    }
}
