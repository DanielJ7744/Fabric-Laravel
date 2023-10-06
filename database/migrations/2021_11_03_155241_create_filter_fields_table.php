<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('key');
            $table->unsignedBigInteger('system_id');
            $table->foreign('system_id')->references('id')->on('systems');
            $table->unsignedTinyInteger('entity_id');
            $table->foreign('entity_id')->references('id')->on('entities');
            $table->unique(['key', 'system_id', 'entity_id']);
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
        Schema::dropIfExists('filter_fields');
    }
}
