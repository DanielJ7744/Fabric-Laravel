<?php

use App\Models\Fabric\FilterOperator;
use App\Models\Fabric\FilterType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InsertFilterTypeFilterOperatorValues extends Migration
{
    public $skipPrimaryKeyChecks = true;

    protected const NOT_EQUALS_KEY = 'Not Equals';

    protected const EQUALS_KEY = 'Equals';

    protected const LESS_THAN_KEY = 'Less Than';

    protected const LESS_THAN_OR_EQUAL_KEY = 'Less Than or Equal';

    protected const GREATER_THAN_KEY = 'Greater Than';

    protected const GREATER_THAN__OR_EQUAL_KEY = 'Greater Than or Equal';

    private const FILTER_TYPE_OPERATORS = [
        'string' => [
            [self::EQUALS_KEY, '='], [self::NOT_EQUALS_KEY, '!=']
        ],
        'integer' => [
            [self::EQUALS_KEY, '='], [self::NOT_EQUALS_KEY, '!='], [self::LESS_THAN_KEY, '<'], [self::GREATER_THAN_KEY, '>'],
            [self::LESS_THAN_OR_EQUAL_KEY, '<='], [self::GREATER_THAN__OR_EQUAL_KEY, '>=']
        ],
        'double' => [
            [self::EQUALS_KEY, '='], [self::NOT_EQUALS_KEY, '!='], [self::LESS_THAN_KEY, '<'], [self::GREATER_THAN_KEY, '>'],
            [self::LESS_THAN_OR_EQUAL_KEY, '<='], [self::GREATER_THAN__OR_EQUAL_KEY, '>=']
        ],
        'boolean' => [
            [self::EQUALS_KEY, '='], [self::NOT_EQUALS_KEY, '!=']
        ],
        'array' => [
            [self::EQUALS_KEY, '='], [self::NOT_EQUALS_KEY, '!=']
        ],
        'csv' => [
            [self::EQUALS_KEY, '='], [self::NOT_EQUALS_KEY, '!=']
        ],
        'NULL' => [
            [self::EQUALS_KEY, '='], [self::NOT_EQUALS_KEY, '!=']
        ],
        'time' => [
            [self::EQUALS_KEY, '='], [self::NOT_EQUALS_KEY, '!='], ['Before', '<'], ['After', '>']
        ],
        'timestamp' => [
            [self::EQUALS_KEY, '='], [self::NOT_EQUALS_KEY, '!='], ['Before', '<'], ['After', '>']
        ],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (FilterType::all() as $type) {
            $filterTypeOperators = self::FILTER_TYPE_OPERATORS[$type->key];
            foreach ($filterTypeOperators as $filterTypeOperator) {
                $filterOperator = FilterOperator::firstWhere([
                    ['name', $filterTypeOperator[0]],
                    ['key', $filterTypeOperator[1]]
                ]);
                if (!$filterOperator) {
                    continue;
                }

                if (DB::table('filter_type_filter_operator')
                    ->where([['filter_type_id', $type->id], ['filter_operator_id', $filterOperator->id]])
                    ->exists()) {
                    continue;
                }

                DB::table('filter_type_filter_operator')->insert([
                    'filter_type_id' => $type->id,
                    'filter_operator_id' => $filterOperator->id
                ]);
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
        Schema::dropIfExists('filter_type_filter_operator');
    }
}
