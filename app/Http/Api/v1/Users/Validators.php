<?php

namespace App\Http\Api\v1\Users;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'company',
        'roles',
        'integrations',
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = ['name', 'email'];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = ['name', 'email', 'include_deleted_users'];

    /**
     * Get resource validation rules.
     *
     * @param mixed|null $record
     *      the record being updated, or null if creating a resource.
     *
     * @return mixed
     */
    protected function rules($record = null): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:46'],
            'email' => ['required', 'email', 'unique:users,email,' . ($record !== null ? $record->id : 'null')],
            'telephone' => ['regex:/^([0-9\s\-\+\(\)]*)$/', 'nullable'],
        ];
    }

    protected function queryRules(): array
    {
        return [];
    }
}
