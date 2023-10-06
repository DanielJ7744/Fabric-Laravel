<?php

namespace App\Queries;

use App\Models\Fabric\EventLog;
use App\Queries\BaseQuery;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventLogQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = EventLog::class;

    /**
     * Create the query builder.
     *
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->with(['audit', 'user'])
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::exact('area')->ignore('all')
            ]);
    }
}
