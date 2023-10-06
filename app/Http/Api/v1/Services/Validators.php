<?php

namespace App\Http\Api\v1\Services;

use App\Rules\Cron;
use App\Rules\Timezone;
use CloudCreativity\LaravelJsonApi\Contracts\Validation\ValidatorInterface;
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
        'integration',
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = [];

    public function create(array $document): ValidatorInterface
    {
        $validator = parent::create($document);
        $validator
            ->sometimes('serviceDetails.description', ['required', 'max:255', 'min:1'], function () {
                return true;
            })
            ->sometimes('serviceDetails.schedule', ['required', 'max:50', new Cron()], function () {
                return true;
            })
            ->sometimes('serviceDetails.pageSize', ['required', 'integer', 'gt:0'], function () {
                return true;
            })->sometimes('serviceDetails.maxRetries', ['required', 'integer', 'gte:0', 'lte:15'], function () {
                return true;
            })->sometimes('serviceDetails.fromOptions.dateFormat', ['required'], function () {
                return true;
            })->sometimes('serviceDetails.fromOptions.timezone', ['required', new Timezone()], function () {
                return true;
            })->sometimes('serviceDetails.toOptions.dateFormat', ['required',], function () {
                return true;
            })->sometimes('serviceDetails.toOptions.timezone', ['required', new Timezone()], function () {
                return true;
            })->sometimes('entity.id', ['required', 'exists:entities,id'], function () {
                return true;
            })->sometimes(
                'sourceIntegrationSystem.relationships.integration.data.id',
                ['required', 'exists:integrations,id'],
                function () {
                    return true;
                })
            ->sometimes(
                'sourceIntegrationSystem.relationships.system.data.id',
                ['required', 'exists:systems,id'],
                function () {
                    return true;
                })
            ->sometimes(
                'destinationIntegrationSystem.relationships.system.data.id',
                ['required', 'exists:systems,id'],
                function () {
                    return true;
                });

        return $validator;
    }

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
        return [];
    }

    protected function queryRules(): array
    {
        return [];
    }
}
