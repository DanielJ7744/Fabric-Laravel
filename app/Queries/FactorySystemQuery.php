<?php

namespace App\Queries;

use App\Models\Fabric\FactorySystem;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FactorySystemQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = FactorySystem::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()->allowedIncludes([
            'factory',
            'system',
            'entity',
            'schemas',
            'integration',
            'serviceOption',
        ])->allowedFilters([
            AllowedFilter::exact('factory_id'),
            AllowedFilter::exact('system_id'),
            AllowedFilter::exact('entity_id'),
            AllowedFilter::exact('direction'),
            AllowedFilter::exact('integration_id'),
        ]);
    }
}
