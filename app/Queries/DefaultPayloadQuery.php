<?php

namespace App\Queries;

use App\Models\Fabric\DefaultPayload;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DefaultPayloadQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = DefaultPayload::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes([
                'factorySystemSchema',
            ])
            ->allowedFilters([
                AllowedFilter::exact('factory_system_schema_id')
            ]);
    }
}
