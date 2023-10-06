<?php

namespace App\Models\Fabric;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Collection;

class ReportSyncResult implements UrlRoutable
{
    private int $id;
    private ?Collection $integrationsNoEntities;
    private ?Collection $entities;
    private ?int $resultsOffset;
    private ?int $totalResults;
    private ?Collection $countsPerIntegration;
    private ?array $pagesPerIntegration;
    private ?array $availableEntities;

    public function __construct(array $resultsData)
    {
        $this->id = 1;
        $this->integrationsNoEntities = $resultsData['integrations_no_entities'];
        $this->entities = $resultsData['entities'];
        $this->resultsOffset = $resultsData['results_offset'];
        $this->totalResults = $resultsData['total_results'];
        $this->countsPerIntegration = $resultsData['counts_per_integration'];
        $this->pagesPerIntegration = $resultsData['pages_per_integration'];
        $this->availableEntities = $resultsData['available_entities'];
    }

    public function getRouteKey(): int
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

    public function getIntegrationsNoEntities(): ?Collection
    {
        return $this->integrationsNoEntities;
    }

    public function getEntities(): ?Collection
    {
        return $this->entities;
    }

    public function getResultsOffset(): ?int
    {
        return $this->resultsOffset;
    }

    public function getTotalResults(): ?int
    {
        return $this->totalResults;
    }

    public function getCountsPerIntegration(): ?Collection
    {
        return $this->countsPerIntegration;
    }

    public function getPagesPerIntegration(): ?array
    {
        return $this->pagesPerIntegration;
    }

    public function getAvailableEntities(): ?array
    {
        return $this->availableEntities;
    }
}
