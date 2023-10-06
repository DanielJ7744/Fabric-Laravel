<?php

namespace App\Models\Fabric;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Collection;

class ReportSyncFilterOption implements UrlRoutable
{
    private int $id;
    private ?Collection $integrations;
    private ?Collection $systemChains;
    private ?Collection $statuses;
    private ?Collection $types;

    public function __construct(array $filterOptionsData)
    {
        $this->id = 1;
        $this->integrations = $filterOptionsData['integrations'];
        $this->systemChains = $filterOptionsData['system_chains'];
        $this->statuses = $filterOptionsData['statuses'];
        $this->types = $filterOptionsData['types'];
    }

    public function getRouteKey(): string
    {
        return $this->id;
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function resolveRouteBinding($value)
    {
        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIntegrations(): ?Collection
    {
        return $this->integrations;
    }

    public function getSystemChains(): ?Collection
    {
        return $this->systemChains;
    }

    public function getStatuses(): ?Collection
    {
        return $this->statuses;
    }

    public function getTypes(): ?Collection
    {
        return $this->types;
    }
}
