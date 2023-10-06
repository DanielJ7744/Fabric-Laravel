<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeenOnDashboardToAlertManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alert_manager', function (Blueprint $table) {
            $table->boolean('seen_on_dashboard')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alert_manager', function (Blueprint $table) {
            $table->dropColumn('seen_on_dashboard');
        });
    }
}
