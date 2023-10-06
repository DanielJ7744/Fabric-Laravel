<?php

use App\Models\Fabric\FilterOperator;
use App\Models\Fabric\FilterType;
use Illuminate\Database\Seeder;

class FilterTypeSeeder extends Seeder
{
    private const TYPES = [
        'string' => [
            'Text', 'Value'
        ],
        'integer' => [
            'Number', '3'
        ],
        'double' => [
            'Decimal', '3.14'
        ],
        'boolean' => [
            'True/False', 'True'
        ],
        'array' => [
            'Array', 'Value 1, Value 2, Value 3'
        ],
        'csv' => [
            'CSV', 'Value 1, Value 2, Value 3'
        ],
        'NULL' => [
            'Empty', NULL
        ],
        'TIME' => [
            'Time', '-1 day'
        ],
        'TIMESTAMP' => [
            'Timestamp', '-1 day'
        ],
        'netsuite_string' => [
            'Text', 'Value'
        ],
        'netsuite_boolean' => [
            'True/False', 'True'
        ],
        'netsuite_numeric' => [
            'Numeric', '3'
        ],
        'netsuite_time' => [
            'Time', '-1 day'
        ],
        'netsuite_timestamp' => [
            'Timestamp', '-1 day'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::TYPES as $key => [$name, $placeholder]) {
            if (FilterType::where(['key' => $key])->doesntExist()) {
                FilterType::create(['name' => $name, 'key' => $key, 'placeholder' => $placeholder]);
            }
        }
    }
}
