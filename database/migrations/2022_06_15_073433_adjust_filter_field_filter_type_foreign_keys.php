<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdjustFilterFieldFilterTypeForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('filter_field_filter_type', function (Blueprint $table) {
            $table->dropForeign(['filter_field_id']);
            $table->dropForeign(['filter_type_id']);

            $table
                ->foreign('filter_field_id')
                ->references('id')
                ->on('filter_fields')
                ->onDelete('cascade');
            $table
                ->foreign('filter_type_id')
                ->references('id')
                ->on('filter_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('filter_field_filter_type', function (Blueprint $table) {
            $table->dropForeign(['filter_field_id']);
            $table->dropForeign(['filter_type_id']);

            $table
                ->foreign('filter_field_id')
                ->references('id')
                ->on('filter_fields');
            $table
                ->foreign('filter_type_id')
                ->references('id')
                ->on('filter_types');
        });
    }
}
