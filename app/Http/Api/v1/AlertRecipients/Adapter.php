<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertRecipients;

use App\Models\Alerting\AlertGroups;
use App\Models\Alerting\AlertRecipients;
use App\Models\Fabric\Company;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Eloquent\BelongsTo;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
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
        parent::__construct(new AlertRecipients(), $paging);
    }

    /**
     * Perform actions needed to create the record
     *
     * @param ResourceObject $resource
     *
     * @return AlertRecipients
     */
    protected function createRecord(ResourceObject $resource): AlertRecipients
    {
        $attributes = $resource->getAttributes()->toArray();
        $companyId = 0;
        if (Auth::user()->company) {
            $companyId = Auth::user()->company->id;
            Company::findOrFail($companyId);
        }

        try {
            //our validator says that one of these must be set
            if (isset($attributes['email'])) {
                return AlertRecipients::where('company_id', $companyId)->where('email', $attributes['email'])->firstOrFail();
            }

            if (isset($attributes['email'], $attributes['name'], $attributes['user_id'])) {
                unset($attributes['email'], $attributes['name']);
            }

            return AlertRecipients::where('company_id', $companyId)->where('user_id', $attributes['user_id'])->firstOrFail();
        } catch (Exception $exception) {
            return $this->createRecipient($attributes, (int)$companyId);
        }
    }

    /**
     * Attempt to create the alert recipient
     *
     * @param array $attributes
     * @param int $companyId
     *
     * @return AlertRecipients
     */
    protected function createRecipient(array $attributes, int $companyId): AlertRecipients
    {
        try {
            $attributes['company_id'] = $companyId;

            return new AlertRecipients($attributes);
        } catch (Exception $exception) {
            $error = new Error(
                null,
                null,
                500,
                null,
                'Failed to create recipient',
                sprintf('Failed to create recipient %s', $exception->getMessage())
            );
            throw new JsonApiException($error, 500);
        }
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return Builder|void
     */
    protected function filter($query, Collection $filters)
    {
        $groupId = $filters->get('group_id');
        $serviceId = $filters->get('service_id');
        if (Auth::user()->company) {
            $group = AlertGroups::where('company_id', Auth::user()->company->id);
            if ($groupId && $serviceId) {
                $groupAndService = $group->where('id', (int)$groupId)->where('service_id', (int)$serviceId)->first();
                if ($groupAndService) {
                    return $query->where('group_id', $groupId)->where('service_id', $serviceId);
                }
            }

            if ($groupId) {
                $group = $group->where('id', (int)$groupId)->first();
                if ($group) {
                    return $query->where('group_id', $groupId);
                }
            }

            if ($serviceId) {
                $service = $group->where('service_id', (int)$serviceId)->first();
                if ($service) {
                    return $query->where('service_id', (int)$serviceId);
                }
            }

            return $query->where('group_id', null);
        }
        $this->filterWithScopes($query, $filters);
    }

    protected function group(): BelongsTo
    {
        return $this->belongsTo();
    }
}
