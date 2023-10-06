<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPatchworksRoleToRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->tinyInteger('patchworks_role')->default(0);
        });

        DB::table('roles')->where('name', 'patchworks admin')->update(['patchworks_role' => 1]);
        DB::table('roles')->where('name', 'patchworks user')->update(['patchworks_role' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('patchworks_role');
        });
    }
}
