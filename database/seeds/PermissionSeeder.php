<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolePermissions = collect([
            'client user' => [
                'search owned companies',
                'read owned companies',
                'search integrations',
                'read owned integrations',
                'search client roles',
                'search entities',
                'read entities',
                'search filter-templates',
                'read filter-templates',
                'read service-logs',
                'create service-logs',
                'search service-logs',
                'update service-logs',
                'search report-sync-results',
                'search report-sync-filter-options',
                'search system-types',
                'search filter-fields',
                'read filter-fields',
                'search event-logs',
                'search authorisation-types',
                'create authorisation-types',
                'read authorisation-types',
                'search system-authorisation-types',
                'read system-authorisation-types',
                'search credentials',
                'read credentials',
                'search services',
                'read services',
                'create services',
                'update services',
                'delete services',
                'search company integrations',
                'search company-profiles',
                'read company-profiles',
                'search maps',
                'read maps',
                'create maps',
                'update maps',
                'delete maps',
                'create mappings',
                'delete mappings',
                'read mappings',
                'search map-values',
                'read map-values',
                'create map-values',
                'update map-values',
                'delete map-values',
                'search connectors',
                'read connectors',
                'create connectors',
                'update connectors',
                'search factories',
                'read factories',
                'search service-templates',
                'read service-templates',
                'search company users',
                'read company users',
                'search alert-groups',
                'read alert-groups',
                'update alert-groups',
                'create alert-groups',
                'delete alert-groups',
                'read alert-manager',
                'search alert-manager',
                'create alert-manager',
                'delete alert-manager',
                'read alert-configs',
                'search alert-configs',
                'update alert-configs',
                'create alert-configs',
                'delete alert-configs',
                'read alert-scheduler',
                'search alert-scheduler',
                'update alert-scheduler',
                'create alert-scheduler',
                'delete alert-scheduler',
                'update alert-manager',
                'create company-profiles',
                'update company-profiles',
                'delete company-profiles',
                'create integration-users',
                'delete integration-users',
                'create credentials',
                'update credentials',
                'delete credentials',
                'read integrations',
                'read system-types',
                'search filter-types',
                'read filter-types',
                'search filter-operators',
                'read filter-operators',
                'search factory-systems',
                'read factory-systems',
                'search systems',
                'read systems',
                'search users',
                'read users',
                'read subscriptions',
                'search subscriptions',
                'read factory-system-schemas',
                'search factory-system-schemas',
                'create entities',
                'update entities',
                'delete entities',
                'create factory-systems',
                'create factory-system-schemas',
                'update factory-system-schemas',
                'delete factory-system-schemas',
                'create service-templates',
                'search webhooks',
                'read webhooks',
                'search system-event-types',
                'read system-event-types',
                'search inbound-apis',
                'create inbound-apis',
                'read inbound-apis',
                'update inbound-apis',
                'search inbound-credentials',
                'create inbound-credentials',
                'read inbound-credentials',
                'update inbound-credentials',
                'delete inbound-credentials',
                'search inbound-endpoints',
                'read inbound-endpoints',
                'create inbound-endpoints',
                'update inbound-endpoints',
                'delete inbound-endpoints',
                'search oauth-clients',
                'read oauth-clients',
                'create oauth-clients',
                'update oauth-clients',
                'delete oauth-clients',
                'search service-options',
                'read service-options',
                'search default-payloads',
                'read default-payloads',
                'read service-template-options',
                'search service-template-options',
                'read mappings',
                'create mappings',
                'delete mappings',
                'search factory-system-service-options',
                'read factory-system-service-options',
                'search payment-maps',
                'create payment-maps',
                'search shipment-maps',
                'create shipment-maps',
            ],
            'client admin' => [
                'delete connectors',
                'update company users',
                'create company users',
                'delete company users',
                'read alert-recipients',
                'search alert-recipients',
                'update alert-recipients',
                'create alert-recipients',
                'delete alert-recipients',
                'create integrations',
                'update integrations',
                'delete integrations',
                'search roles',
                'read roles',
                'create users',
                'update users',
                'delete users',
                'update companies',
                'create webhooks',
                'update webhooks',
                'delete webhooks',
            ],
            'patchworks user' => [
                'search companies',
                'read companies',
                'create companies',
                'assign companies',
                'delete companies',
                'delete service-logs',
            ],
            'patchworks admin' => [
                'search permissions',
                'read permissions',
                'create permissions',
                'update permissions',
                'delete permissions',
                'create roles',
                'update roles',
                'delete roles',
                'create systems',
                'update systems',
                'delete systems',
                'create filter-templates',
                'update filter-templates',
                'delete filter-templates',
                'create filter-types',
                'update filter-types',
                'delete filter-types',
                'create admin-integration',
                'search admin-integration',
                'read admin-integration',
                'update authorisation-types',
                'delete authorisation-types',
                'create system-authorisation-types',
                'update system-authorisation-types',
                'delete system-authorisation-types',
                'create system-types',
                'update system-types',
                'delete system-types',
                'add subscription',
                'remove subscription',
                'update factory-systems',
                'update service-templates',
                'delete service-templates',
                'delete factory-systems',
                'create service-options',
                'update service-options',
                'delete service-options',
                'create filter-fields',
                'update filter-fields',
                'delete filter-fields',
                'create filter-operators',
                'update filter-operators',
                'delete filter-operators',
                'create default-payloads',
                'update default-payloads',
                'delete default-payloads',
                'create service-template-options',
                'update service-template-options',
                'delete service-template-options',
                'create factories',
                'update factories',
                'delete factories',
                'create factory-system-service-options',
                'update factory-system-service-options',
                'delete factory-system-service-options',
            ],
        ]);

        // Ensure all permissions exist
        $rolePermissions->flatten()->each(fn ($permissionName) => Permission::firstOrCreate(['name' => $permissionName]));

        // Delete unused permissions
        Permission::whereNotIn('name', $rolePermissions->flatten())->delete();

        // Sync each role's permissions - Each role is assigned all permissions from the lower roles as well as it's own
        collect([
            'client user' => ['client user'],
            'client admin' => ['client user', 'client admin'],
            'patchworks user' => ['client user', 'client admin', 'patchworks user'],
            'patchworks admin' => ['client user', 'client admin', 'patchworks user', 'patchworks admin'],
        ])->each(function ($permissionGroups, $roleName) use ($rolePermissions) {
            $permissionIds = Permission::whereIn('name', $rolePermissions->only($permissionGroups)->flatten())->pluck('id');

            Role::firstOrCreate(['name' => $roleName, 'patchworks_role' => Str::contains($roleName, 'patchworks')])->permissions()->sync($permissionIds);
        });
    }
}
