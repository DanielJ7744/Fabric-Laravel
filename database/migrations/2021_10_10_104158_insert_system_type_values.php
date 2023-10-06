<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertSystemTypeValues extends Migration
{
    public $skipPrimaryKeyChecks = true;

    private const SYSTEMTYPES = [
        'eCommerce',
        'ERP',
        'WMS/3PL',
        'Accounting',
        'POS',
        'Marketplaces',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::SYSTEMTYPES as $systemType) {
            if (DB::table('system_types')->where('name', $systemType)->doesntExist()) {
                DB::table('system_types')->insert(['name' => $systemType, 'active' => 1, 'created_at' => now()]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (self::SYSTEMTYPES as $systemType) {
            if (DB::table('system_types')->where('name', $systemType)->exists()) {
                DB::table('system_types')->where('name', $systemType)->delete();
            }
        }
    }
}
