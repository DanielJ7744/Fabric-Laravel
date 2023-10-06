<?php

namespace App\Models\Fabric;

use App\Http\Helpers\ServiceFilterHelper;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Service implements UrlRoutable
{
    private string $id;
    private int $serviceId;
    private int $status;
    private ?string $description;
    private string $fromFactory;
    private string $fromEnvironment;
    private ?string $fromOptions;
    private ?Collection $filters;
    private ?string $fromMapping;
    private string $toFactory;
    private ?string $toEnvironment;
    private ?string $toOptions;
    private ?string $toMapping;
    private string $schedule;
    private Integration $integration;
    private ?Entity $fromEntity;
    private ?System $fromSystem;
    private ?FilterTemplate $fromFilterTemplate;
    private ?Entity $toEntity;
    private ?System $toSystem;

    public function __construct(
        array $serviceData,
        Integration $integration,
        ?Entity $fromEntity,
        ?System $fromSystem,
        ?FilterTemplate $fromFilterTemplate,
        ?Entity $toEntity,
        ?System $toSystem,
        ?Collection $filters = null
    ) {
        $this->id = sprintf('%s|%s', $integration->username, $serviceData['id']);
        $this->serviceId = $serviceData['id'];
        $this->status = $serviceData['status'];
        $this->description = $serviceData['description'];
        $this->fromFactory = $serviceData['from_factory'];
        $this->fromEnvironment = $serviceData['from_environment'];
        $this->fromOptions = $serviceData['from_options'];
        $this->fromMapping = $serviceData['from_mapping'];
        $this->toFactory = $serviceData['to_factory'];
        $this->toEnvironment = $serviceData['to_environment'];
        $this->toOptions = $serviceData['to_options'];
        $this->toMapping = $serviceData['to_mapping'];
        $this->schedule = $serviceData['schedule'];
        $this->integration = $integration;
        $this->fromEntity = $fromEntity;
        $this->fromSystem = $fromSystem;
        $this->fromFilterTemplate = $fromFilterTemplate;
        $this->toEntity = $toEntity;
        $this->toSystem = $toSystem;
        $this->filters = $filters;
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

    public function getServiceId(): int
    {
        return $this->serviceId;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus (bool $status): void
    {
        $this->status = $status;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getFromFactory(): string
    {
        return $this->fromFactory;
    }

    public function getFromEnvironment(): string
    {
        return $this->fromEnvironment;
    }

    public function getFromOptions(): ?string
    {
        return $this->fromOptions;
    }

    public function setFromOptions(?string $input): void
    {
        $input = json_decode($input, true);
        $fromOptions = json_decode($this->fromOptions, true);
        foreach ($input as $key => $value) {
            $setter = sprintf('set%s', ucwords($key));
            method_exists($this, $setter)
                ? Arr::set($fromOptions, $key, self::$setter($value))
                : Arr::set($fromOptions, $key, $value);
        }

        $this->fromOptions = json_encode($fromOptions, JSON_PRETTY_PRINT);
    }

    public function getFromMapping(): ?string
    {
        return $this->fromMapping;
    }

    public function setFromMapping(?string $fromMapping): void
    {
        $this->fromMapping = $fromMapping;
    }

    public function getToFactory(): string
    {
        return $this->toFactory;
    }

    public function getToEnvironment(): ?string
    {
        return $this->toEnvironment;
    }

    public function getToOptions(): ?string
    {
        return $this->toOptions;
    }

    public function setToOptions(?string $toOptions): void
    {
        $this->toOptions = $toOptions;
    }

    public function getToMapping(): ?string
    {
        return $this->toMapping;
    }

    public function setToMapping(?string $toMapping): void
    {
        $this->toMapping = $toMapping;
    }

    public function getSchedule(): string
    {
        return $this->schedule;
    }

    public function setSchedule(string $schedule): void
    {
        $this->schedule = $schedule;
    }

    public function getIntegration(): Integration
    {
        return $this->integration;
    }

    public function getFromEntity(): ?Entity
    {
        return $this->fromEntity;
    }

    public function getFromSystem(): ?System
    {
        return $this->fromSystem;
    }

    public function getFromFilterTemplate(): ?FilterTemplate
    {
        return $this->fromFilterTemplate;
    }

    public function getToEntity(): ?Entity
    {
        return $this->toEntity;
    }

    public function getToSystem(): ?System
    {
        return $this->toSystem;
    }

    public function getFilters(): ?Collection
    {
        return $this->filters;
    }

    /**
     * Filters are an array within the from options JSON.
     * This method is called to set the relevant JSON within the from options JSON.
     *
     * @param array $filters
     *
     * @return array
     */
    protected function setFilters(array $filters): array
    {
        if (ServiceFilterHelper::isJSONFilterStructure($filters)) {
            return $filters;
        }

        return ServiceFilterHelper::destructFilters($this->fromSystem, $this->fromEntity, collect($filters));
    }
}
