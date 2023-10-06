<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertRecipients;

use App\Rules\CompanyName;
use CloudCreativity\LaravelJsonApi\Rules\HasOne;
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
        'group'
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = ['group_id', 'name', 'user_id', 'email', 'disabled'];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = ['group_id', 'name', 'user_id', 'email', 'disabled'];

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
            'group_id' => ['nullable', 'integer', 'min:1'],
            'user_id' => ['required_without:email', 'nullable', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255', new CompanyName()],
            'email' => ['required_without:user_id', 'nullable', 'email'],
            'disabled' => ['required', 'integer', 'min:0', 'max:1'],
            'group' => [
                new HasOne('alert-groups'),
            ],
        ];
    }

    protected function queryRules(): array
    {
        return [];
    }
}
