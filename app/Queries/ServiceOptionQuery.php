<?php

namespace App\Queries;

use App\Models\Fabric\ServiceOption;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceOptionQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = ServiceOption::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedFilters([
                'key'
            ])
            ->defaultSort('-key')
            ->allowedSorts([
                'key',
            ]);
    }
}
