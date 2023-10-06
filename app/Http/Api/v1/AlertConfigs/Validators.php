<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertConfigs;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [
        'service_id',
        'throttle_value',
        'alert_threshold',
        'alert_throttle_value',
        'alert_status',
    ];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = [
        'service_id',
        'throttle_value',
        'alert_threshold',
        'alert_throttle_value',
        'alert_status',
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
            'service_id' => ['required_without:service_ids', 'int', 'min:0'],
            'service_ids' => ['required_without:service_id'],
            'throttle_value' => ['nullable', 'int', 'max:999'],
            'error_alert_status' => ['required', 'int', 'max:3'],
            'error_alert_threshold' => ['nullable', 'int', 'max:999'],
            'warning_alert_status' => ['required', 'int', 'max:3'],
            'warning_alert_threshold' => ['nullable','int', 'max:999'],
            'frequency_alert_status' => ['required', 'int', 'max:3'],
            'frequency_alert_threshold' => ['nullable', 'int', 'max:999999'],
            'alert_frequency' => ['required', 'string'],
            'alert_status' => ['required', 'int', 'max:3'],
        ];
    }

    protected function queryRules(): array
    {
        return [];
    }
}
