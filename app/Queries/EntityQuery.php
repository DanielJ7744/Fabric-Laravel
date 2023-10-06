<?php

namespace App\Queries;

use App\Models\Fabric\Entity;
use App\Queries\BaseQuery;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EntityQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = Entity::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes(['integration'])
            ->allowedFilters([
                AllowedFilter::exact('integration_id')
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
            ]);
    }
}
