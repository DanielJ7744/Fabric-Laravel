<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertFilterOperatorValues extends Migration
{
    public $skipPrimaryKeyChecks = true;

    private const OPERATORS = [
        'Equals' => '=',
        'Not Equals' => '!=',
        'Less Than' => '<',
        'Before' => '<',
        'Less Than or Equal' => '<=',
        'Greater Than' => '>',
        'After' => '>',
        'Greater Than or Equal' => '>=',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::OPERATORS as $operatorName => $operatorKey) {
            if (DB::table('filter_operators')->where([['key', $operatorKey], ['name', $operatorName]])->doesntExist()) {
                DB::table('filter_operators')->insert(['name' => $operatorName, 'key' => $operatorKey]);
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
        foreach (self::OPERATORS as $operatorName => $operatorKey) {
            if (DB::table('filter_operators')->where('key', $operatorKey)->exists()) {
                DB::table('filter_operators')->where('key', $operatorKey)->delete();
            }
        }
    }
}
