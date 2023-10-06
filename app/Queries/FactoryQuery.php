<?php

namespace App\Queries;

use App\Models\Fabric\Factory;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FactoryQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = Factory::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedFilters([
                AllowedFilter::exact('name')
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
            ]);
    }
}
