<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropIntegrationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('integration_user');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('integration_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('integration_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('integration_id')->references('id')->on('integrations');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->unique(['integration_id', 'user_id']);
        });
    }
}
