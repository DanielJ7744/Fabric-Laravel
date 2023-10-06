<?php

declare(strict_types=1);

namespace App\Http\Api\v1\AlertManager;

use App\Models\Alerting\AlertManager;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Eloquent\HasMany;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
        parent::__construct(new AlertManager(), $paging);
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return Builder|void
     */
    protected function filter($query, Collection $filters)
    {
        $serviceId = $filters->get('service_id');
        if (Auth::user()->company) {
            $companyId = Auth::user()->company->id;
            $manager = AlertManager::where('company_id', $companyId);
            if ($companyId && $serviceId) {
                $groupAndService = $manager->where('company_id', (int)$companyId)->where('service_id', (int)$serviceId)->first();
                if ($groupAndService) {
                    return $query->where('company_id', $companyId)->where('service_id', $serviceId);
                }
            }

            if ($serviceId) {
                $service = $manager->where('service_id', (int)$serviceId)->first();
                if ($service) {
                    return $query->where('service_id', (int)$serviceId);
                }
            }

            return $query->where('company_id', $companyId);
        }
        $this->filterWithScopes($query, $filters);
    }

    protected function recipients(): HasMany
    {
        return $this->hasMany();
    }
}
