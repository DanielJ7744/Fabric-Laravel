<?php

namespace App\Queries;

use App\Models\Tapestry\ServiceLog;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ServiceLogQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = ServiceLog::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes(['service'])
            ->allowedFilters([
                AllowedFilter::exact('service_id'),
                AllowedFilter::scope('due_before'),
                AllowedFilter::scope('due_after'),
                AllowedFilter::scope('started_before'),
                AllowedFilter::scope('started_after'),
                AllowedFilter::scope('finished_before'),
                AllowedFilter::scope('finished_after'),
            ])
            ->defaultSort('-started_at')
            ->allowedSorts([
                'due_at',
                'started_at',
                'finished_at',
                'id'
            ]);
    }
}
