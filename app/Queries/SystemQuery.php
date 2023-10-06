<?php

namespace App\Queries;

use App\Models\Fabric\System;
use App\Queries\BaseQuery;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SystemQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = System::class;

    /**
     * Create the query builder.
     *
     * @return \Spatie\QueryBuilder\QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes([
                'systemType',
                'systemAuthorisationTypes',
                'systemAuthorisationTypes.authorisationType',
            ])
            ->allowedFilters([
                AllowedFilter::exact('name'),
                AllowedFilter::scope('pushes_to'),
                AllowedFilter::scope('pulls_from'),
            ]);
    }
}
