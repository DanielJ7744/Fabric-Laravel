<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertServiceRecipients;

use CloudCreativity\LaravelJsonApi\Rules\HasMany;
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
        'recipients',
        'group',
        'recipientGroup'
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = ['service_id', 'recipient_id', 'group_id'];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = ['service_id', 'recipient_id', 'group_id'];

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
            'service_id' => ['required_without:service_ids', 'integer', 'min:1'],
            'service_ids' => ['required_without:service_id'],
            'recipient_id' => ['required_without:group_id', 'integer', 'min:1'],
            'group_id' => ['required_without:recipient_id', 'integer', 'min:1'],
            'recipient' => [
                new HasMany('alert-recipients'),
            ],
            'group' => [
                new HasMany('alert-groups')
            ]
        ];
    }

    protected function queryRules(): array
    {
        return [];
    }
}
