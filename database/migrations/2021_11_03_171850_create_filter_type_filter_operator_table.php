<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterTypeFilterOperatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_type_filter_operator', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('filter_type_id');
            $table->foreign('filter_type_id')->references('id')->on('filter_types');
            $table->unsignedBigInteger('filter_operator_id');
            $table->foreign('filter_operator_id')->references('id')->on('filter_operators');
            $table->unique(['filter_type_id', 'filter_operator_id'], 'filter_type_filter_operator_type_id_operator_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filter_type_filter_operator');
    }
}
