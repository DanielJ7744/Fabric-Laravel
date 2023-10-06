<?php

namespace App\Queries;

use App\Models\Fabric\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserQuery extends BaseQuery
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
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
            ]);
    }
}
