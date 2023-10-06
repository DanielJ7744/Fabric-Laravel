<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertManager;

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
        'configs',
        'recipients',
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [
        'company_id',
        'service_id',
        'config_id',
        'recipient_id',
        'alert_type',
        'send_from',
        'dispatched_at',
        'failed_at',
    ];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = [
        'company_id',
        'service_id',
        'config_id',
        'recipient_id',
        'alert_type',
        'send_from',
        'dispatched_at',
        'failed_at',
    ];

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
            'seen_on_dashboard' => ['required', 'int', 'min:0']
        ];
    }

    protected function queryRules(): array
    {
        return [];
    }
}
