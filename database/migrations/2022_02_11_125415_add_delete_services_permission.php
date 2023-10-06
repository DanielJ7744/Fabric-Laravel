<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddDeleteServicesPermission extends Migration
{
    public $skipPrimaryKeyChecks = true;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $deleteServices = Permission::create(['name' => 'delete services']);

        Role::whereIn('name', ['patchworks admin', 'client admin'])->get()
            ->each->givePermissionTo($deleteServices);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::whereName('delete services')->first()->delete();
    }
}
