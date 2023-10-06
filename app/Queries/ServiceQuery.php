<?php

namespace App\Queries;

use App\Models\Tapestry\Service;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = Service::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedFields([
                'id',
                'status',
                'description',
                'from_factory',
                'from_environment',
                'to_factory',
                'to_environment',
                'username',
                'schedule',
                'timeout',
                'from_options',
                'from_mapping',
                'to_options',
                'to_mapping',
                'idle_timeout',
                'run_count',
                'run_id',
                'deleted_at',
                'created_at',
                'updated_at',
                'service_template_id',
                'billable',
            ])
            ->allowedIncludes(['integration', 'alertConfigs', 'serviceTemplate', 'serviceTemplate.source', 'serviceTemplate.destination'])
            ->allowedFilters([
                AllowedFilter::exact('username'),
                'billable'
            ]);
    }
}
