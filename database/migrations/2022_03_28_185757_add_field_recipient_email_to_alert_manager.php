<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldRecipientEmailToAlertManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alert_manager', function (Blueprint $table) {
            $table->string('recipient_email', 250)->nullable()->after('recipient_id');
            $table->string('email_template', 100)->default('emails.general')->after('recipient_email');
            $table->json('meta_data')->nullable()->after('email_template');
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
            $table->dropColumn('recipient_email');
            $table->dropColumn('email_template');
            $table->dropColumn('meta_data');
        });
    }
}
