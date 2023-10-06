<?php

use Illuminate\Database\Seeder;
use App\Models\Fabric\Integration;
use App\Models\Fabric\Company;

class IntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::all()->each(function (Company $company) {
            //DB unique constraint is on name so if an entry with name already exists we need to leave it
            $matchingIntegration = Integration::where('name', $company->name)->first();
            if (is_null($matchingIntegration)) {
                Integration::create([
                    'company_id' => $company->id,
                    'name' => $company->name,
                    'username' => strtolower(str_replace(' ', '_', $company->name)),
                    'server' => 'tapestry-nginx-app',
                    'active' => false
                ]);
            }
        });
    }
}
