<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterFieldFilterOperatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_field_filter_operator', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('filter_field_id');
            $table->foreign('filter_field_id')->references('id')->on('filter_fields');
            $table->unsignedBigInteger('filter_operator_id');
            $table->foreign('filter_operator_id')->references('id')->on('filter_operators');
            $table->unique(['filter_field_id', 'filter_operator_id'], 'filter_field_filter_operator_field_id_operator_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filter_field_filter_operator');
    }
}
