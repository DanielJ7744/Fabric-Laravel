<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdjustFilterFieldFilterOperatorForeignKeys extends Migration
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

        Schema::table('filter_field_filter_operator', function (Blueprint $table) {
            $table->dropForeign(['filter_field_id']);
            $table->dropForeign(['filter_operator_id']);

            $table
                ->foreign('filter_field_id')
                ->references('id')
                ->on('filter_fields')
                ->onDelete('cascade');
            $table
                ->foreign('filter_operator_id')
                ->references('id')
                ->on('filter_operators')
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

        Schema::table('filter_field_filter_operator', function (Blueprint $table) {
            $table->dropForeign(['filter_field_id']);
            $table->dropForeign(['filter_type_id']);

            $table
                ->foreign('filter_field_id')
                ->references('id')
                ->on('filter_fields');
            $table
                ->foreign('filter_operator_id')
                ->references('id')
                ->on('filter_operators');
        });
    }
}
