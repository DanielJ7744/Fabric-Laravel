<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertServiceRecipients;

use App\Models\Alerting\AlertServiceRecipients;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use CloudCreativity\LaravelJsonApi\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Exception;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class Adapter extends AbstractAdapter
{
    protected $defaultWith = [
        'recipients',
        'group'
    ];

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
        parent::__construct(new AlertServiceRecipients(), $paging);
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     */
    protected function filter($query, Collection $filters)
    {
        $this->filterWithScopes($query, $filters);
    }

    protected function recipients(): HasMany
    {
        return $this->hasMany();
    }

    protected function groups(): HasMany
    {
        return $this->hasMany();
    }

    /**
     * Attempt to create the record
     *
     * @param ResourceObject $resource
     *
     * @return AlertServiceRecipients
     */
    protected function createRecord(ResourceObject $resource): AlertServiceRecipients
    {
        $attributes = $resource->getAttributes()->toArray();
        $applyToAll = $this->shouldApplyToAll($attributes);
        if (!$this->isValidDataSet($attributes)) {
            throw new JsonApiException(
                $this->generateError('Required data not set', 'Apply to all is set to true but service ids not populated'),
                500
            );
        }

        if ($applyToAll === false) {
            return parent::createRecord($resource);
        }

        return $this->applyToAll($attributes, $resource);
    }

    /**
     * Should the call be apply to all
     *
     * @param array $attributes
     *
     * @return bool
     */
    protected function shouldApplyToAll(array $attributes): bool
    {
        return $attributes['apply_to_all'] ?? false;
    }

    /**
     * Is the valid data set
     *
     * @param array $attributes
     *
     * @return bool
     */
    protected function isValidDataSet(array $attributes): bool
    {
        if (
            !$this->shouldApplyToAll($attributes)
        ) {
            return isset($attributes['service_id']) && (isset($attributes['group_id']) || isset($attributes['recipient_id']));
        }

        return
            isset($attributes['service_ids']) && !empty($attributes['service_ids'])
            && (
                (isset($attributes['group_ids']) && is_array($attributes['group_ids']) && !empty($attributes['group_ids']))
                || (isset($attributes['recipient_ids']) && is_array($attributes['recipient_ids'])
                    && !empty($attributes['recipient_ids']))
            );
    }

    /**
     * Find matching relationship data
     *
     * @param array $relationships
     * @param string $recipientType
     * @param string $recipientId
     *
     * @return array|null
     */
    protected function findMatchingRelationship(array $relationships, string $recipientType, string $recipientId): ?array
    {
        foreach ($relationships as $relationshipType => $relationship) {
            if ($relationshipType !== $recipientType || !isset($relationship['data'])) {
                continue;
            }

            foreach ($relationship['data'] as $data) {
                if (!isset($data['id']) || $data['id'] !== $recipientId) {
                    continue;
                }

                return $data;
            }
        }

        return null;
    }

    /**
     * Attempt to apply to all
     *
     * @param array $attributes
     * @param ResourceObject $resource
     *
     * @return AlertServiceRecipients
     */
    protected function applyToAll(array $attributes, ResourceObject $resource): AlertServiceRecipients
    {
        $serviceIds = $attributes['service_ids'];
        foreach ($serviceIds as $index => $serviceId) {
            if (
                isset($attributes['recipient_ids'])
                && is_array($attributes['recipient_ids'])
                && !empty($attributes['recipient_ids'])
            ) {
                foreach ($attributes['recipient_ids'] as $recipientId) {
                    $matchingRelationship = $this->findMatchingRelationship(
                        $resource->getRelationships()->toArray(),
                        'recipient',
                        (string)$recipientId
                    );
                    $recipientResource = new ResourceObject(
                        'alert-service-recipients',
                        null,
                        ['service_id' => $serviceId, 'recipient_id' => $recipientId],
                        ['recipient' => ['data' => [$matchingRelationship]]]
                    );
                    try {
                        $record = AlertServiceRecipients::where([
                            'service_id' => $serviceId,
                            'recipient_id' => $recipientId
                        ])->firstOrFail();
                        $this->destroy($record);
                        $recipientRecord = $this->fillAndPersist($record, $recipientResource, new EncodingParameters(), true);
                    } catch (Exception $exception) {
                        $recipientRecord = $this->fillAndPersist(
                            parent::createRecord($recipientResource),
                            $recipientResource,
                            new EncodingParameters(),
                            false
                        );
                    }
                }
            }

            if (
                isset($attributes['group_ids'])
                && is_array($attributes['group_ids'])
                && !empty($attributes['group_ids'])
            ) {
                foreach ($attributes['group_ids'] as $groupId) {
                    $matchingRelationship = $this->findMatchingRelationship($resource->getRelationships()->toArray(), 'group', (string)$groupId);
                    $groupResource = new ResourceObject('alert-service-recipients', null, ['service_id' => $serviceId, 'group_id' => $groupId], ['group' => ['data' => [$matchingRelationship]]]);
                    try {
                        $record = AlertServiceRecipients::where('service_id', $serviceId)->where('group_id', $groupId)->firstOrFail();
                        $this->destroy($record);
                        $groupRecord = $this->fillAndPersist($record, $groupResource, new EncodingParameters(), true);
                    } catch (Exception $exception) {
                        $groupRecord = $this->fillAndPersist(parent::createRecord($groupResource), $groupResource, new EncodingParameters(), true);
                    }
                }
            }

            if (array_key_last($serviceIds) === $index) {
                return $recipientRecord ?? $groupRecord;
            }
        }

        throw new JsonApiException(
            $this->generateError('Unable to apply all to recipients', 'Data is not populated correctly'),
            500
        );
    }

    /**
     * Throw an error
     *
     * @param string $title
     * @param string $detail
     *
     * @return void
     */
    protected function generateError(string $title, string $detail): Error
    {
        return new Error(
            null,
            null,
            500,
            null,
            $title,
            $detail
        );
    }
}
