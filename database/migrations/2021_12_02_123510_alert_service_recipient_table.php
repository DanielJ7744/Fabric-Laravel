<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertServiceRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_service_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_id')->unsigned();
            $table->integer('recipient_id')->unsigned()->nullable();
            $table->integer('group_id')->unsigned()->nullable();
            $table->timestamps();
            $table->unique(['service_id', 'recipient_id']);
            $table->unique(['service_id', 'group_id']);
            $table->index(['service_id', 'recipient_id', 'group_id'], 'svc_rpt_grp_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_service_recipients');
    }
}
