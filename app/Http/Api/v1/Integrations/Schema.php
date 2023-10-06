<?php

namespace App\Http\Api\v1\Integrations;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'integrations';

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'name' => $resource->name,
            'created-at' => $resource->created_at ? $resource->created_at->toAtomString() : null,
            'updated-at' => $resource->updated_at ? $resource->updated_at->toAtomString() : null,
            'username' => $resource->username,
            'server' => $resource->server,
            'active' => $resource->active,
        ];
    }

    public function getRelationships($integration, $isPrimary, array $includeRelationships)
    {
        return [
            'company' => [
                self::DATA => function () use ($integration) {
                    return $integration->company;
                },
            ],
        ];
    }
}
