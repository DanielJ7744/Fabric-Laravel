<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFilterFieldsAddDefaults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filter_fields', function (Blueprint $table) {
            $table->boolean('default')->default(false);
            $table->string('default_value')->nullable();
            $table->unsignedBigInteger('default_type_id')->nullable();
            $table->foreign('default_type_id')->references('id')->on('filter_types');
            $table->unsignedBigInteger('default_operator_id')->nullable();
            $table->foreign('default_operator_id')->references('id')->on('filter_operators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('filter_fields', function (Blueprint $table) {
            $table->dropForeign('filter_fields_default_type_id_foreign');
            $table->dropForeign('filter_fields_default_operator_id_foreign');
            $table->dropColumn(['default', 'default_value', 'default_type_id', 'default_operator_id']);
        });
    }
}
