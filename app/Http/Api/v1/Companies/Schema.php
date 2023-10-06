<?php

namespace App\Http\Api\v1\Companies;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'companies';

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
            'company_website' => $resource->company_website,
            'company_phone' => $resource->company_phone,
            'company_email' => $resource->company_email,
            'trial_ends_at' => $resource->trial_ends_at,
            'created_at' => $resource->created_at ? $resource->created_at->toAtomString() : null,
            'updated_at' => $resource->updated_at ? $resource->updated_at->toAtomString() : null,
        ];
    }
}
