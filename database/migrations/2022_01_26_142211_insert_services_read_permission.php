<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertServicesReadPermission extends Migration
{
    public bool $skipPrimaryKeyChecks = true;

    private const PERMISSIONS = [
        'read services',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::PERMISSIONS as $permission) {
            if (DB::table('permissions')->where('name', $permission)->doesntExist()) {
                DB::table('permissions')->insert(['name' => $permission, 'guard_name' => 'web']);
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
        foreach (self::PERMISSIONS as $permission) {
            if (DB::table('permissions')->where('name', $permission)->exists()) {
                DB::table('permissions')->where('name', $permission)->delete();
            }
        }
    }
}
