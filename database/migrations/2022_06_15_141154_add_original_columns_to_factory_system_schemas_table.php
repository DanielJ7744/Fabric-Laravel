<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginalColumnsToFactorySystemSchemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('factory_system_schemas', function (Blueprint $table) {
            $table->string('original_type', 24)->nullable()->after('schema');
            $table->text('original_schema')->nullable()->after('original_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factory_system_schemas', function (Blueprint $table) {
            $table->dropColumn('original_type');
            $table->dropColumn('original_schema');
        });
    }
}
