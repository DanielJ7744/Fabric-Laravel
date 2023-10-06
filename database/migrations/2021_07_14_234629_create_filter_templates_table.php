<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('system_id')->nullable();
            $table->unsignedTinyInteger('entity_id')->nullable();
            $table->foreign('system_id')->references('id')->on('systems');
            $table->foreign('entity_id')->references('id')->on('entities');
            $table->string('filter_key')->default('');;
            $table->longText('template')->nullable();
            $table->longText('note')->nullable();
            $table->string('pw_value_field', 30)->nullable();
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
        Schema::dropIfExists('filter_templates');
    }
}
