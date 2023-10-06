<?php

namespace App\Http\Api\v1\Systems;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'systems';

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
            'system_type_id' => $resource->system_type_id,
            'name' => $resource->name,
            'factory_name' => $resource->factory_name,
            'slug' => $resource->slug,
            'website' => $resource->website,
            'oauth' => $resource->oauth,
            'description' => $resource->description,
            'active' => $resource->active,
            'popular' => $resource->popular,
            'created-at' => $resource->created_at ? $resource->created_at->toAtomString() : null,
            'updated-at' => $resource->updated_at ? $resource->updated_at->toAtomString() : null,
        ];
    }
}
