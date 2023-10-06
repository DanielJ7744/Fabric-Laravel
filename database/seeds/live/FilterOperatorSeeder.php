<?php

use App\Models\Fabric\FilterOperator;
use Illuminate\Database\Seeder;

class FilterOperatorSeeder extends Seeder
{
    private const OPERATORS = [
        'Equals' => '=',
        'Not Equals' => '!=',
        'Less Than' => '<',
        'Before' => '<',
        'Less Than or Equal' => '<=',
        'Greater Than' => '>',
        'After' => '>',
        'Greater Than or Equal' => '>=',
        'Starts With' => '=>',
        'Any Of' => '=',
        'None Of' => '!=',
        'Not Greater Than' => '!>',
        'Not Greater Than Or Equal To' => '!>=',
        'Not Less Than' => '!<',
        'Not Less Than Or Equal To' => '!<=',
        'Not After' => '!>',
        'Not Before' => '!<',
        'On Or After' => '>=',
        'On Or Before' => '<=',
        'Not On Or After' => '!>=',
        'Not On Or Before' => '!<=',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::OPERATORS as $operatorName => $operatorKey) {
            if (FilterOperator::where(['name' => $operatorName, 'key' => $operatorKey])->doesntExist()) {
                FilterOperator::create(['name' => $operatorName, 'key' => $operatorKey]);
            }
        }
    }
}
