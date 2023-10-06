<?php

namespace App\Queries;

use App\Models\Fabric\Subscription;
use Spatie\QueryBuilder\QueryBuilder;

class SubscriptionQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = Subscription::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedFilters([
                'name',
                'transactions',
                'upgrade',
                'product_type'
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
                'transactions',
            ]);
    }
}
