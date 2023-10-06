<?php

namespace App\Queries;

use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

class RoleQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = Role::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes(['permissions'])
            ->allowedFilters(['name']);
    }
}
