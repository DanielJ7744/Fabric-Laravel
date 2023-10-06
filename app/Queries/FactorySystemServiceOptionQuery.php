<?php

namespace App\Queries;

use App\Models\Fabric\FactorySystemServiceOption;
use Spatie\QueryBuilder\QueryBuilder;

class FactorySystemServiceOptionQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = FactorySystemServiceOption::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedFilters([
                'factory_system_id',
                'service_option_id',
            ])
            ->allowedIncludes([
                'factorySystem',
                'serviceOption',
            ]);
    }
}
