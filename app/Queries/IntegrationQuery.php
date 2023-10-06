<?php

namespace App\Queries;

use App\Models\Fabric\Integration;
use App\Queries\BaseQuery;
use Spatie\QueryBuilder\QueryBuilder;

class IntegrationQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = Integration::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes([
                'company',
                'services',
                'users',
                'factorySystemSchemas',
                'factorySystems'
            ])
            ->allowedFilters(['active']);
    }
}
