<?php

namespace App\Queries;

use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Fabric\AuthorisationType;

class AuthorisationTypeQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = AuthorisationType::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedFilters([
                'name'
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
            ]);
    }
}
