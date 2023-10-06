<?php

namespace App\Queries;

use App\Models\Fabric\Company;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = Company::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes([
                'integrations',
                'users',
                'eventLogs',
                'subscriptions'
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'name'
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
                'id',
                'created_at',
                'updated_at'
            ]);
    }
}
