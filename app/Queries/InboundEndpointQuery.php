<?php

namespace App\Queries;

use App\Models\Fabric\InboundEndpoint;
use App\Queries\BaseQuery;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InboundEndpointQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = InboundEndpoint::class;

    /**
     * Create the query builder.
     *
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedFilters([
                AllowedFilter::exact('service_id')
            ]);
    }
}
