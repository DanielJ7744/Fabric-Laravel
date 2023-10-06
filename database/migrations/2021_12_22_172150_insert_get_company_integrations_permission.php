<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertGetCompanyIntegrationsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::table('permissions')->where('name', 'search company integrations')->doesntExist()) {
            DB::table('permissions')->insert(['name' => 'search company integrations', 'guard_name' => 'web']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('permissions')->where('name', 'search company integrations')->delete();
    }
}
