<?php

namespace App\Queries;

use App\Models\Fabric\User;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class AdminUserQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = User::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes(['company', 'roles'])
            ->allowedFilters([
                AllowedFilter::trashed(),
                AllowedFilter::exact('company_id'),
                AllowedFilter::scope('search'),
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
            ]);
    }
}
