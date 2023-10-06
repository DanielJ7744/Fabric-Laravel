<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RemoveOldRolePermissionMigrations extends Migration
{
    public $skipPrimaryKeyChecks = true;

    /**
     * Migrations to remove
     *
     * @var array
     */
    public $migrations = [
        '2021_07_20_122436_insert_initial_role_has_permissions',
        '2021_07_20_145102_insert_entities_permissions',
        '2021_07_20_145806_insert_entities_role_has_permissions',
        '2021_07_20_153949_update_client_admin_permissions',
        '2021_07_21_095949_update_client_user_permissions',
        '2021_08_02_164557_update_patchworks_user_permissions',
        '2021_08_03_082131_insert_filter_templates_permissions',
        '2021_08_03_082540_insert_filter_templates_role_has_permissions',
        '2021_08_04_134529_insert_services_permissions',
        '2021_08_04_134637_insert_services_role_has_permission',
        '2021_08_09_095159_insert_servicelog_permissions',
        '2021_08_09_095331_insert_servicelog_role_has_permissions',
        '2021_08_12_142829_insert_report_sync_counts_permissions',
        '2021_08_12_142929_insert_report_sync_counts_role_has_permissions',
        '2021_08_13_124135_insert_report_sync_initials_permissions',
        '2021_08_13_124155_insert_report_sync_initials_role_has_permissions',
        '2021_08_13_124219_insert_report_sync_filter_options_permissions',
        '2021_08_13_124233_insert_report_sync_filter_options_role_has_permissions',
        '2021_08_17_151204_update_role_permissions',
        '2021_08_26_141812_insert_integration_system_permissions',
        '2021_08_26_141837_insert_integration_system_role_has_permissions',
        '2021_10_05_100003_insert_mappings_permissions',
        '2021_10_05_100412_add_mapping_permissions_and_service_update_permission',
        '2021_10_08_171817_insert_system_types_permissions',
        '2021_10_08_171828_insert_system_types_role_has_permissions',
        '2021_11_04_104608_insert_filter_fields_permissions',
        '2021_11_04_105201_insert_filter_fields_role_has_permissions',
        '2021_11_04_161908_insert_filter_types_permissions',
        '2021_11_04_162013_insert_filter_types_role_has_permissions',
        '2021_11_04_165858_insert_filter_operators_permissions',
        '2021_11_04_170354_insert_filter_operators_role_has_permissions',
        '2021_11_05_114924_insert_alert_mapping_permissions',
        '2021_11_16_104352_insert_services_create_role_has_permission',
        '2021_12_15_121549_insert_system_entity_permissions',
        '2021_12_15_121709_insert_system_entity_role_has_permissions',
        '2021_12_22_183122_attach_get_company_integrations_to_roles',
        '2022_01_04_152241_insert_event_logs_permissions',
        '2022_01_04_152722_attach_event_logs_permissions_to_roles',
        '2022_01_12_160421_insert_alert_mapping_role_has_permissions',
        '2022_01_13_153211_insert_authorisation_types_permissions',
        '2022_01_13_153211_insert_system_authorisation_types_permissions',
        '2022_01_13_153517_insert_authorisation_types_role_has_permissions',
        '2022_01_13_153518_insert_system_authorisation_types_role_has_permissions',
        '2022_01_14_094357_remove_integration_system_role_has_permissions',
        '2022_01_14_094458_remove_integration_system_permissions',
        '2022_01_14_095229_insert_credential_permissions',
        '2022_01_14_095317_insert_credential_role_has_permissions',
        '2022_01_21_081321_insert_alert_manager_update_permissions',
        '2022_01_21_081421_insert_alert_manager_role_has_update_permissions',
        '2022_01_25_154540_insert_company_profiles_permissions',
        '2022_01_25_154546_insert_company_groups_permissions',
        '2022_01_25_154640_insert_linked_company_groups_permissions',
        '2022_01_25_155601_add_company_group_permissions_to_roles',
        '2022_01_25_155601_add_company_profile_permissions_to_roles',
        '2022_01_25_155601_add_linked_company_group_permissions_to_roles',
        '2022_01_26_142308_insert_services_read_role_has_permission',
        '2022_01_31_150001_insert_company_group_users_permissions',
        '2022_01_31_150100_add_company_group_users_permissions_to_roles',
        '2022_02_10_121102_insert_maps_permissions',
        '2022_02_10_121303_insert_maps_role_has_permissions',
        '2022_02_10_121906_insert_map_values_permissions',
        '2022_02_10_121929_insert_map_values_role_has_permissions',
        '2022_02_25_114950_insert_integration_users_permissions',
        '2022_02_25_115120_add_integration_users_permissions_to_roles',
        '2022_03_01_080927_insert_admin_integration_permissions',
        '2022_03_01_080927_insert_admin_integration_role_has_permissions',
        '2022_03_09_120626_insert_connector_permissions',
        '2022_03_09_120714_insert_connector_role_has_permissions',
        '2022_03_18_075746_insert_search_read_admin_integration_permissions',
        '2022_03_18_075840_insert_admin_integration_role_has_search_read_permissions',
        '2022_03_23_095602_insert_service_template_permissions',
        '2022_03_23_095922_insert_service_template_role_has_permissions',
        '2022_03_30_165509_insert_factory_system_permissions',
        '2022_03_30_165523_insert_factory_system_role_has_permissions',
        '2022_04_05_082351_insert_factory_permissions',
        '2022_04_05_082351_insert_factory_role_has_permissions'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('migrations')->whereIn('migration', $this->migrations)->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $latestBatch = DB::table('migrations')->max('batch');

        $migrations = collect($this->migrations)->map(fn ($migrationName) => [
            'batch' => $latestBatch + 1,
            'migration' => $migrationName,
        ])->toArray();

        DB::table('migrations')->insert($migrations);
    }
}
