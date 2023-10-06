<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertRecipients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_recipients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id');
            $table->integer('group_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->boolean('disabled')->default(0);
            $table->timestamps();
            $table->unique(['company_id', 'user_id']);
            $table->unique(['company_id', 'email']);
            $table->index(['company_id', 'user_id', 'group_id'], 'company_user_group_idx');
            $table->index(['company_id', 'email', 'group_id'], 'company_email_group_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_recipients');
    }
}
