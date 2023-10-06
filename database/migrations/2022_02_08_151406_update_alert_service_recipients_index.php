<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class UpdateAlertServiceRecipientsIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alert_service_recipients', function (Blueprint $table) {
            $table->index(['service_id', 'recipient_id'], 'svc_rpt_idx');
            $table->index(['service_id', 'group_id'], 'svc_grp_idx');
            $table->dropIndex('svc_rpt_grp_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alert_service_recipients', function (Blueprint $table) {
            $table->dropIndex('svc_rpt_idx');
            $table->dropIndex('svc_grp_idx');
            $table->index(['service_id', 'recipient_id', 'group_id'], 'svc_rpt_grp_idx');
        });
    }
}
