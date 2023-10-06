<?php

use App\Models\Fabric\Integration;
use App\Models\Companies\CompanyGroup;
use Illuminate\Database\Seeder;

class CompanyGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allIntegrationData = Integration::all();
        foreach ($allIntegrationData as $integrationData) {
            $existingCompanyGroup = CompanyGroup::where('company_id', $integrationData->company_id)->where('group_name', $integrationData->name)->first();
            if ($existingCompanyGroup !== null) {
                continue;
            }
            $result = CompanyGroup::create([
                'company_id' => $integrationData->company_id,
                'group_name' => $integrationData->name,
                'idx_table_name' => sprintf('idx_%s', $integrationData->username),
            ]);
            $integrationData->update(['company_group_id' => $result->id]);
        }
    }
}
