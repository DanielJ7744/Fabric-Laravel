<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertRecipients;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'alert-recipients';

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
        return [];
    }

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'group_id' => $resource->group_id,
            'user_id' => $resource->user_id,
            'name' => $resource->name,
            'email' => $resource->email,
            'disabled' => $resource->disabled,
            'created-at' => $resource->created_at ? $resource->created_at->toAtomString() : null,
            'updated-at' => $resource->updated_at ? $resource->updated_at->toAtomString() : null,
        ];
    }

    /**
     * @param object $service
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array
     */
    public function getRelationships($alertGroup, $isPrimary, array $includeRelationships): array
    {
        return [
            'group' => [
                self::DATA => function () use ($alertGroup) {
                    return $alertGroup->group;
                },
            ],
            'user' => [
                self::DATA => function () use ($alertGroup) {
                    return $alertGroup->user;
                },
            ],
        ];
    }
}
