<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ReplaceSearchCompanyIntegrationsPermission extends Migration
{
    public $skipPrimaryKeyChecks = true;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove the old permission
        Permission::whereName('search company integrations')->first()->delete();

        // Create and assign the existing permission
        $searchIntegrations = Permission::whereName('search integrations')->first();

        Role::whereIn('name', ['client user', 'client admin'])->get()
            ->each->givePermissionTo($searchIntegrations);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revoke the existing permission
        $clientRoles = Role::whereIn('name', ['client user', 'client admin'])->get();
        $clientRoles->each->revokePermissionTo('search integrations');

        // Restore the removed permission
        $searchCompanyIntegrations = Permission::create(['name' => 'search company integrations']);

        $clientRoles->each->givePermissionTo($searchCompanyIntegrations);
    }
}
