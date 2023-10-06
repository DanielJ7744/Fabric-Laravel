<?php

use App\Models\Fabric\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Patchworks', 'Company 1', 'Company 2'] as $name) {
            factory(Company::class)->states('active')->create(['name' => $name]);
        }
    }
}
