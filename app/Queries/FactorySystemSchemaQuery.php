<?php

namespace App\Queries;

use App\Models\Fabric\FactorySystemSchema;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FactorySystemSchemaQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = FactorySystemSchema::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()->allowedIncludes([
            'factorySystem',
            'factorySystem.factory',
            'factorySystem.system',
            'factorySystem.entity',
            'integration',
            'defaultPayload'
        ])->allowedFilters([
            AllowedFilter::exact('factory_system_id'),
            AllowedFilter::exact('factorySystem.system_id'),
            AllowedFilter::exact('integration_id'),
            AllowedFilter::exact('type'),
        ]);
    }
}
