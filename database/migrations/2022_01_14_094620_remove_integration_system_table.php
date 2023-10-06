<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIntegrationSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('integration_system');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('integration_system', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('integration_id');
            $table->unsignedBigInteger('system_id');
            $table->foreign('integration_id')->references('id')->on('integrations');
            $table->foreign('system_id')->references('id')->on('systems');
            $table->json('credentials')->default(null);
            $table->timestamps();
        });
    }
}
