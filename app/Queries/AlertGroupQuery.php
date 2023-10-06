<?php

namespace App\Queries;

use App\Models\Alerting\AlertGroups;
use Spatie\QueryBuilder\QueryBuilder;

class AlertGroupQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = AlertGroups::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes(['recipients']);
    }
}
