<?php

namespace App\Http\Api\v1\Users;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    protected $resourceType = 'users';

    /**
     * @param $resource
     *      the domain record being serialized.
     *
     * @return string
     */
    public function getId($resource): string
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param $resource
     *      the domain record being serialized.
     *
     * @return array
     */
    public function getAttributes($resource): array
    {
        return [
            'name' => $resource->name,
            'email' => $resource->email,
            'telephone' => $resource->telephone,
            'mobile' => $resource->mobile,
            'avatar_url' => $resource->avatar_url,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
            'deleted_at' => $resource->deleted_at
        ];
    }

    public function getRelationships($user, $isPrimary, array $includeRelationships)
    {
        return [
            'company' => [
                self::DATA => function () use ($user) {
                    return $user->company;
                },
            ],
            'integrations' => [
                self::DATA => function () use ($user) {
                    return $user->company_id
                        ? $user->company->integrations
                        : collect();
                }
            ],
            'roles' => [
                self::DATA => function () use ($user) {
                    return $user->roles;
                }
            ]
        ];
    }
}
