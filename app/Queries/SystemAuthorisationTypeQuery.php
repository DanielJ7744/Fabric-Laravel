<?php

namespace App\Queries;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Fabric\SystemAuthorisationType;

class SystemAuthorisationTypeQuery extends BaseQuery
{
    /**
     * The model for this query.
     */
    public $model = SystemAuthorisationType::class;

    /**
     * Create the query builder.
     *
     * @return QueryBuilder
     */
    public function builder(): QueryBuilder
    {
        return parent::builder()
            ->allowedIncludes([
                'system',
                'authorisationType'
            ])
            ->allowedFilters([
                AllowedFilter::exact('system_id')
            ]);
    }
}
