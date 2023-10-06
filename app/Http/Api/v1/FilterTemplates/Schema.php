<?php

namespace App\Http\Api\v1\FilterTemplates;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'filter-templates';

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
            'filter_key' => $resource->filter_key,
            'template' => $resource->template,
            'note' => $resource->note,
            'pw_value_field' => $resource->pw_value_field,
            'created-at' => $resource->created_at ? $resource->created_at->toAtomString() : null,
            'updated-at' => $resource->updated_at ? $resource->updated_at->toAtomString() : null,
        ];
    }

    public function getRelationships($filterTemplate, $isPrimary, array $includeRelationships)
    {
        return [
            'system' => [
                self::DATA => function () use ($filterTemplate) {
                    return $filterTemplate->factorySystem()->first()->system();
                },
            ],
            'entity' => [
                self::DATA => function () use ($filterTemplate) {
                    return $filterTemplate->factorySystem()->first()->entity()->first();
                }
            ]
        ];
    }
}
