<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateRemainingFactoryNamesInSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('systems', function (Blueprint $table) {
            DB::statement("update `systems` set `factory_name` = 'Bigcommerce' where `name` = 'Bigcommerce';");
            DB::statement("update `systems` set `factory_name` = 'Prestashop' where `name` = 'Prestashop';");
            DB::statement("update `systems` set `factory_name` = 'Linnworks' where `name` = 'Linnworks';");
            DB::statement("update `systems` set `factory_name` = 'Vend' where `name` = 'Vend';");
            DB::statement("update `systems` set `factory_name` = 'Xml' where `name` = 'Xml';");
            DB::statement("update `systems` set `factory_name` = 'TorqueAPI' where `name` = 'TorqueAPI';");
            DB::statement("update `systems` set `factory_name` = 'Cybertill' where `name` = 'Cybertill';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Bigcommerce';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Prestashop';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Linnworks';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Vend';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Xml';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'TorqueAPI';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Cybertill';");
    }
}
