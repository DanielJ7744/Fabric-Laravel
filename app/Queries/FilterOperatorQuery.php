<?php

namespace App\Queries;

use App\Models\Fabric\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;

class FilterOperatorQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = FilterOperator::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes(['types', 'fields']);
    }
}
