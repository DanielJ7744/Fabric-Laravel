<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertFilterTypeValues extends Migration
{
    public $skipPrimaryKeyChecks = true;

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
        'time' => [
            'Time', '-1 day'
        ],
        'timestamp' => [
            'Timestamp', '-1 day'
        ],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::TYPES as $key => [$name, $placeholder]) {
            if (DB::table('filter_types')->where(['key' => $key])->doesntExist()) {
                DB::table('filter_types')->insert(['name' => $name, 'key' => $key, 'placeholder' => $placeholder]);
            }
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (self::TYPES as $key => $value) {
            if (DB::table('filter_types')->where(['key' => $key])->exists()) {
                DB::table('filter_types')->where(['key' => $key])->delete();
            }
        }
    }
}
