<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_entity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('system_id');
            $table->unsignedTinyInteger('entity_id');
            $table->string('direction');
            $table->foreign('system_id')->references('id')->on('systems');
            $table->foreign('entity_id')->references('id')->on('entities');
            $table->timestamps();
            $table->unique(['system_id', 'entity_id', 'direction']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_entity');
    }
}
