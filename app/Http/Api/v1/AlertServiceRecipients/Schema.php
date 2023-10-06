<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertServiceRecipients;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'alert-service-recipients';

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    public function getIncludePaths(): array
    {
        return [
            'recipients',
            'group'
        ];
    }

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'service_id' => $resource->service_id,
            'recipient_id' => $resource->recipient_id,
            'group_id' => $resource->group_id
        ];
    }

    /**
     * @param object $service
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array
     */
    public function getRelationships($alertServiceRecipients, $isPrimary, array $includeRelationships): array
    {
        return [
            'recipients' => [
                self::DATA => function () use ($alertServiceRecipients) {
                    return $alertServiceRecipients->recipients;
                },
            ],
            'group' => [
                self::DATA => function () use ($alertServiceRecipients) {
                    return $alertServiceRecipients->group;
                }
            ],
        ];
    }
}
