<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemAuthorisationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_authorisation_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('system_id');
            $table->unsignedBigInteger('authorisation_type_id');
            $table->json('credentials_schema')->nullable();
            $table->timestamps();

            $table->unique(['system_id', 'authorisation_type_id'], 'sys_id_auth_id_unique');
            $table->index(['id', 'system_id', 'authorisation_type_id'], 'id_sys_id_auth_id_index');

            $table->foreign('system_id')
                ->references('id')
                ->on('systems');
            $table->foreign('authorisation_type_id')
                ->references('id')
                ->on('authorisation_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_authorisation_types');
    }
}
