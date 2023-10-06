<?php

namespace App\Queries;

use App\Models\Fabric\Webhook;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WebhookQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = Webhook::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()->allowedIncludes([
            'integration',
            'service',
            'eventType',
        ])->allowedFilters([
            AllowedFilter::exact('integration_id'),
            AllowedFilter::exact('service_id'),
            AllowedFilter::exact('event_type_id'),
        ]);
    }
}
