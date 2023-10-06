<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultPayloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_payloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('factory_system_schema_id')->unique();
            $table->string('type', 24)->default('json');
            $table->text('payload');
            $table->timestamps();

            $table->foreign('factory_system_schema_id')
                ->references('id')
                ->on('factory_system_schemas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_payloads');
    }
}
