<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertGroups;

use App\Models\Alerting\AlertGroups;
use App\Models\Fabric\Company;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Eloquent\HasMany;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Exception;

class Adapter extends AbstractAdapter
{
    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new AlertGroups(), $paging);
    }

    /**
     * @inheritDoc
     *
     */
    protected function createRecord(ResourceObject $resource): AlertGroups
    {
        $attributes = $resource->getAttributes();
        $companyId = 0;
        if (Auth::user()->company) {
            $companyId = Auth::user()->company->id;
            $company = Company::find($companyId);
            if (!$company) {
                $companyId = 0;
            }
        }
        $name = $attributes['name'];
        $data = [
            'name' => $name,
            'company_id' => $companyId,
        ];

        try {
            return new AlertGroups($data);
        } catch (Exception $exception) {
            $error = new Error(null, null, 500, null, 'Failed to create alert group', sprintf("Failed to alert group %s\n%s", $name, $exception->getMessage()));
            throw new JsonApiException($error, 500);
        }
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        if (Auth::user()->company) {
            $query->where('company_id', Auth::user()->company->id);
        }
        $this->filterWithScopes($query, $filters);
    }

    protected function recipients(): HasMany
    {
        return $this->hasMany();
    }

    /**
     * Delete any related recipients when the group is deleted
     *
     * @param AlertGroups $alertGroups
     *
     * @return void
     */
    protected function deleting(AlertGroups $alertGroups): void
    {
        $alertGroups->recipients()->delete();
    }
}
