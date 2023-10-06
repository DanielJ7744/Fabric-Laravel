<?php

namespace App\Queries;

use App\Models\Fabric\FilterTemplate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FilterTemplateQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = FilterTemplate::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedFilters([
                AllowedFilter::exact('factory_system_id'),
                AllowedFilter::exact('factorySystem.system_id'),
            ])
            ->allowedIncludes([
                'factorySystem',
                'factorySystem.factory',
                'factorySystem.system',
                'factorySystem.entity'
            ]);
    }
}
