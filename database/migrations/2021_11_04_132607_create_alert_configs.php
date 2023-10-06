<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertConfigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('service_id')->nullable();
            $table->integer('throttle_value')->nullable();
            $table->tinyInteger('error_alert_status')->default(0);
            $table->integer('error_alert_threshold')->nullable();
            $table->tinyInteger('warning_alert_status')->default(0);
            $table->integer('warning_alert_threshold')->nullable();
            $table->tinyInteger('frequency_alert_status')->default(0);
            $table->integer('frequency_alert_threshold')->nullable();
            $table->string('alert_frequency')->default('off');
            $table->tinyInteger('alert_status')->default(0);
            $table->tinyInteger('alert_scheduled')->default(0);
            $table->timestamp('next_run_datetime')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'service_id'], 'comp_service_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_configs');
    }
}
