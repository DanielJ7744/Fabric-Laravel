<?php

namespace App\Queries;

use App\Models\Fabric\Integration;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class AdminIntegrationQuery extends BaseQuery
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
            ->allowedIncludes(['company', 'services', 'users'])
            ->allowedFilters([
                'active',
                AllowedFilter::exact('company_id'),
                'name'
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
            ]);
    }
}
