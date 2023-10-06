<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateFactoryNameInSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('systems', function (Blueprint $table) {
            DB::statement("update `systems` set `factory_name` = 'Clover' where `name` = 'Clover';");
            DB::statement("update `systems` set `factory_name` = 'Lightspeed' where `name` = 'Lightspeed';");
            DB::statement("update `systems` set `factory_name` = 'Shopify' where `name` = 'Shopify';");
            DB::statement("update `systems` set `factory_name` = 'Yotpo' where `name` = 'Yotpo';");
            DB::statement("update `systems` set `factory_name` = 'Xero' where `name` = 'Xero';");
            DB::statement("update `systems` set `factory_name` = 'Peoplevox' where `name` = 'Peoplevox';");
            DB::statement("update `systems` set `factory_name` = 'Netsuite' where `name` = 'Netsuite';");
            DB::statement("update `systems` set `factory_name` = 'MagentoTwo' where `name` = 'Magento 2';");
            DB::statement("update `systems` set `factory_name` = 'Khaos' where `name` = 'Khaos';");
            DB::statement("update `systems` set `factory_name` = 'DynamicsNav' where `name` = 'Dynamics Nav';");
            DB::statement("update `systems` set `factory_name` = 'Visualsoft' where `name` = 'Visualsoft';");
            DB::statement("update `systems` set `factory_name` = 'CommerceTools' where `name` = 'CommerceTools';");
            DB::statement("update `systems` set `factory_name` = 'Bleckmann' where `name` = 'Bleckmann';");
            DB::statement("update `systems` set `factory_name` = 'Radial' where `name` = 'Radial';");
            DB::statement("update `systems` set `factory_name` = 'Rebound' where `name` = 'Rebound';");
            DB::statement("update `systems` set `factory_name` = 'Ometria' where `name` = 'Ometria';");
            DB::statement("update `systems` set `factory_name` = 'OmetriaFilePull' where `name` = 'OmetriaFilePull';");
            DB::statement("update `systems` set `factory_name` = 'Demandware' where `name` = 'Demandware';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Clover';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Lightspeed';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Shopify';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Yotpo';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Xero';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Peoplevox';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Netsuite';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Magento 2';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Khaos';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Dynamics Nav';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Visualsoft';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'CommerceTools';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Bleckmann';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Radial';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Rebound';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Ometria';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'OmetriaFilePull';");
        DB::statement("update `systems` set `factory_name` = null where `name` = 'Demandware';");
    }
}
