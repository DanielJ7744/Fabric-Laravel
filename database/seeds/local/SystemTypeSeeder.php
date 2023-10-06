<?php

use App\Models\Fabric\SystemType;
use Illuminate\Database\Seeder;

class SystemTypeSeeder extends Seeder
{
    /**
     * The system types to seed.
     *
     * @var array
     */
    private $types = [
        'eCommerce',
        'ERP',
        'WMS/3PL',
        'Accounting',
        'POS',
        'Marketplaces',
        'CRM',
        'Other',
        'Patchworks',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->types as $name) {
            if (SystemType::where('name', $name)->doesntExist()) {
                factory(SystemType::class)->states('active')->create(['name' => $name]);
            }
        }
    }
}
