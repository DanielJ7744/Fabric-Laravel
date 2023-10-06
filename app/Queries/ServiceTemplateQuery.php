<?php

namespace App\Queries;

use App\Models\Fabric\ServiceTemplate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceTemplateQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = ServiceTemplate::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedFilters([
                AllowedFilter::exact('source.system_id'),
                AllowedFilter::exact('destination.system_id'),
                AllowedFilter::exact('source_factory_system_id'),
                AllowedFilter::exact('destination_factory_system_id'),
                AllowedFilter::scope('integration_id'),
            ])
            ->allowedIncludes([
                'source',
                'destination',
                'serviceTemplateOptions',
                'destination.factory',
                'destination.schemas',
                'destination.system',
                'destination.entity',
                'source.factory',
                'source.schemas',
                'source.system',
                'source.entity',
                'integration'
            ]);
    }
}
