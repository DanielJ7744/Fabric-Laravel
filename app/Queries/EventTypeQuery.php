<?php

namespace App\Queries;

use App\Models\Fabric\EventType;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventTypeQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = EventType::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()->allowedIncludes(['system'])->allowedFilters([AllowedFilter::exact('system_id')]);
    }
}
