<?php

namespace App\Http\Api\v1\Systems;

use App\Rules\SystemName;
use App\Rules\SystemWebsite;
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
    protected $allowedSortParameters = ['name', 'website', 'credentials_schema', 'description'];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = ['name', 'website', 'credentials_schema', 'description'];

    /**
     * Get resource validation rules.
     *
     * @param mixed|null $record
     *      the record being updated, or null if creating a resource.
     * @return mixed
     */
    protected function rules($record = null): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:128', new SystemName()],
            'website' => ['required', 'string', 'min:3', 'max:255', new SystemWebsite()],
            'description' => ['required', 'string', 'min:3', 'max:255'],
            'active' => ['required', 'boolean'],
            'system_type_id' => ['exists:system_types,id', 'required']
        ];
    }

    /**
     * Get query parameter validation rules.
     *
     * @return array
     */
    protected function queryRules(): array
    {
        return [];
    }
}
