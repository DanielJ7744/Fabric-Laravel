<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterFieldFilterTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_field_filter_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('filter_field_id');
            $table->foreign('filter_field_id')->references('id')->on('filter_fields');
            $table->unsignedBigInteger('filter_type_id');
            $table->foreign('filter_type_id')->references('id')->on('filter_types');
            $table->unique(['filter_field_id', 'filter_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filter_field_filter_type');
    }
}
