<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateFilters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('filter_field_filter_operator')->truncate();
        DB::table('filter_field_filter_type')->truncate();
        Schema::dropIfExists('filter_fields');
        Schema::create('filter_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('key');
            $table->unsignedBigInteger('factory_system_id');
            $table->foreign('factory_system_id')->references('id')->on('factory_system');
            $table->unique(['key', 'factory_system_id']);
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
