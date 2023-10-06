<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdjustFactorySystemsForeignKeys extends Migration
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

        Schema::table('factory_system', function (Blueprint $table) {
            $table->dropForeign(['entity_id']);
            $table->dropForeign(['factory_id']);
            $table->dropForeign(['system_id']);

            $table
                ->foreign('entity_id')
                ->references('id')
                ->on('entities')
                ->onDelete('cascade');
            $table
                ->foreign('factory_id')
                ->references('id')
                ->on('factories')
                ->onDelete('cascade');
            $table
                ->foreign('system_id')
                ->references('id')
                ->on('systems')
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

        Schema::table('factory_system', function (Blueprint $table) {
            $table->dropForeign(['entity_id']);
            $table->dropForeign(['factory_id']);
            $table->dropForeign(['system_id']);

            $table->foreign('entity_id')->references('id')->on('entities');
            $table->foreign('factory_id')->references('id')->on('factories');
            $table->foreign('system_id')->references('id')->on('systems');
        });
    }
}
