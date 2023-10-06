<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveInvalidEntities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('system_entity')->truncate();
        DB::table('system_entity_schemas')->truncate();
        DB::table('entities')->truncate();
        Schema::dropIfExists('entities');
        Schema::enableForeignKeyConstraints();

        Schema::create('entities', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name')->unique();
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
    }
}
