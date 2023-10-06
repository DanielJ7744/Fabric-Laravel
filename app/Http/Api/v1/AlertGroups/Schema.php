<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertGroups;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'alert-groups';

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
            'company_id' => $resource->company_id,
            'name' => $resource->name,
            'created-at' => $resource->created_at ? $resource->created_at->toAtomString() : null,
            'updated-at' => $resource->updated_at ? $resource->updated_at->toAtomString() : null,
            'deleted-at' => $resource->deleted_at ? $resource->deleted_at->toAtomString() : null
        ];
    }

    public function getRelationships($alertGroup, $isPrimary, array $includeRelationships)
    {
        return [
            'recipients' => [
                self::DATA => function () use ($alertGroup) {
                    return $alertGroup->recipients;
                },
            ],
        ];
    }
}
