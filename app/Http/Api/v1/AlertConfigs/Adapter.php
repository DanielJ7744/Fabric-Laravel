<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertConfigs;

use Exception;
use App\Models\Alerting\AlertConfigs;
use App\Models\Fabric\Company;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Eloquent\HasMany;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Adapter extends AbstractAdapter
{
    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new AlertConfigs(), $paging);
    }

    /**
     * @inheritDoc
     *
     * @throws \Exception
     */
    protected function createRecord(ResourceObject $resource): AlertConfigs
    {
        $attributes = $resource->getAttributes();
        if (!Auth::user()->company) {
            throw new Exception('No company found for this user.');
        }

        $companyId = Auth::user()->company->id;
        Company::findOrFail($companyId);
        $applyToAll = false;
        if (isset($attributes['apply_to_all'])) {
            $applyToAll = $attributes['apply_to_all'];
            if (
                !isset($attributes['service_ids'])
                || !is_array($attributes['service_ids'])
                || empty($attributes['service_ids'])
                && $applyToAll === true
            ) {
                throw new Exception('No service IDs even though apply to all is set to true');
            }
        }

        $data = [
            'company_id' => $companyId,
            'throttle_value' => $attributes['throttle_value'] ?? null,
            'error_alert_status' => $attributes['error_alert_status'],
            'error_alert_threshold' => $attributes['error_alert_threshold'] ?? null,
            'warning_alert_status' => $attributes['warning_alert_status'],
            'warning_alert_threshold' => $attributes['warning_alert_threshold'] ?? null,
            'frequency_alert_status' => $attributes['frequency_alert_status'],
            'frequency_alert_threshold' => $attributes['frequency_alert_threshold'] ?? null,
            'alert_frequency' => $attributes['alert_frequency'],
            'alert_status' => $attributes['alert_status'],
        ];

        if ($applyToAll !== true) {
            try {
                $data['service_id'] = $attributes['service_id'];

                return new AlertConfigs($data);
            } catch (Exception $exception) {
                $error = new Error(null, null, 500, null, 'Failed to create alert config',
                    sprintf("Failed to create alert config for company id %s\n%s", $companyId, $exception->getMessage())
                );
                throw new JsonApiException($error, 500);
            }
        }

        foreach ($attributes['service_ids'] as $index => $serviceId) {
            $data['service_id'] = $serviceId;
            try {
                //attempt to locate any existing config for this service ID as if it exists we want to update
                $existingConfig = AlertConfigs::where('company_id', $companyId)->where('service_id', $serviceId)->firstOrFail();
                $existingConfig->update([
                    'throttle_value' => $data['throttle_value'],
                    'error_alert_status' => $data['error_alert_status'],
                    'error_alert_threshold' => $data['error_alert_threshold'],
                    'warning_alert_status' => $data['warning_alert_status'],
                    'warning_alert_threshold' => $data['warning_alert_threshold'],
                    'frequency_alert_status' => $data['frequency_alert_status'],
                    'frequency_alert_threshold' => $data['frequency_alert_threshold'],
                    'alert_frequency' => $data['alert_frequency'],
                    'alert_status' => $data['alert_status']
                ]);
                $this->fillAndPersist($existingConfig, $resource, new EncodingParameters(), true);
                if (array_key_last($attributes['service_ids']) === $index) {
                    return $existingConfig;
                }
            } catch (Exception $exception) {
                try {
                    if (array_key_last($attributes['service_ids']) === $index) {
                        return new AlertConfigs($data);
                    }

                    $this->fillAndPersist(new AlertConfigs($data), $resource, new EncodingParameters(), false);
                } catch (Exception $exception) {
                    $error = new Error(null, null, 500, null, 'Failed to create alert config',
                        sprintf("Failed to create alert config for company id %s\n%s", $companyId, $exception->getMessage())
                    );
                    throw new JsonApiException($error, 500);
                }
            }
        }
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        if (Auth::user()->company) {
            $query->where('company_id', Auth::user()->company->id);
        }
        $this->filterWithScopes($query, $filters);
    }

    protected function recipients(): HasMany
    {
        return $this->hasMany();
    }
}
