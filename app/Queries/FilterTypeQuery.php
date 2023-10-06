<?php

namespace App\Queries;

use App\Models\Fabric\FilterType;
use App\Queries\BaseQuery;
use Spatie\QueryBuilder\QueryBuilder;

class FilterTypeQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = FilterType::class;

    /**
     * Create the query builder.
     *
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes(['filterOperator']);
    }
}
