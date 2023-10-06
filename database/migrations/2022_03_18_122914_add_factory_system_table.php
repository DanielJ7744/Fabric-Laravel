<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactorySystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factory_system', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('system_id');
            $table->unsignedTinyInteger('entity_id');
            $table->enum('direction', ['pull', 'push']);
            $table->foreign('entity_id')->references('id')->on('entities');
            $table->foreign('factory_id')->references('id')->on('factories');
            $table->foreign('system_id')->references('id')->on('systems');
            $table->unique(['factory_id', 'system_id', 'direction'], 'factory_system_direction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factory_system');
    }
}
