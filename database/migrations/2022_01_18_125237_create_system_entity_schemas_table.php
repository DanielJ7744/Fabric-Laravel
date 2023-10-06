<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemEntitySchemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_entity_schemas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('system_id');
            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');

            $table->unsignedTinyInteger('entity_id');
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');

            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->string('type', 24)->default('json');
            $table->tinyInteger('source_api_version')->default(1);
            $table->text('data');

            $table->unique(['system_id', 'entity_id', 'source_api_version'], 'system_entity_version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_entity_schemas');
    }
}
