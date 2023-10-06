<?php

namespace App\Queries;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Fabric\FilterField;

class FilterFieldQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = FilterField::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes([
                'factorySystem',
                'factorySystem.factory',
                'factorySystem.system',
                'factorySystem.entity',
                'filterType',
                'filterOperator',
                'filterType.filterOperator',
                'defaultType',
                'defaultOperator'
            ])
            ->allowedFilters([
                'name',
                'key',
                AllowedFilter::exact('factory_system_id'),
                AllowedFilter::exact('default'),
                AllowedFilter::exact('factorySystem.system.id')
            ]);
    }
}
