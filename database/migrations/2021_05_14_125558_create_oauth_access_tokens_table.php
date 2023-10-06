<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('access_token');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('system_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('system_id')->references('id')->on('systems');
            $table->unique(['user_id', 'system_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_access_tokens');
    }
}
