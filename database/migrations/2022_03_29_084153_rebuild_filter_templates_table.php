<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RebuildFilterTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('filter_templates')->truncate();
        Schema::dropIfExists('filter_templates');
        Schema::create('filter_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('factory_system_id');
            $table->foreign('factory_system_id')->references('id')->on('factory_system');
            $table->string('filter_key')->default('');
            $table->longText('template')->nullable();
            $table->longText('note')->nullable();
            $table->longText('pw_value_field')->nullable();
            $table->timestamps();
            $table->unique(['factory_system_id', 'filter_key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('filter_templates')->truncate();
        Schema::dropIfExists('filter_templates');
        Schema::create('filter_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('system_id')->nullable();
            $table->unsignedTinyInteger('entity_id')->nullable();
            $table->foreign('system_id')->references('id')->on('systems');
            $table->foreign('entity_id')->references('id')->on('entities');
            $table->string('filter_key')->default('');
            $table->longText('template')->nullable();
            $table->longText('note')->nullable();
            $table->string('pw_value_field', 30)->nullable();
            $table->timestamps();
        });
    }
}
