<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactorySystemSchemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factory_system_schemas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('factory_system_id');
            $table->unsignedBigInteger('integration_id')->nullable();
            $table->string('type', 24)->default('json');
            $table->text('schema');
            $table->timestamps();

            $table->foreign('factory_system_id')
                ->references('id')
                ->on('factory_system')
                ->onDelete('cascade');

            $table->foreign('integration_id')
                ->references('id')
                ->on('integrations')
                ->onDelete('cascade');

            $table->unique(['factory_system_id', 'integration_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factory_system_schemas');
    }
}
