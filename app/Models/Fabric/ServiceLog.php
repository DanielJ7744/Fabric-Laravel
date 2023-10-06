<?php

namespace App\Models\Fabric;

use Illuminate\Contracts\Routing\UrlRoutable;

class ServiceLog implements UrlRoutable
{
    private int $id;
    private int $serviceId;
    private string $fromFactory;
    private string $fromEnvironment;
    private string $toFactory;
    private string $toEnvironment;
    private string $username;
    private string $requestedBy;
    private string $status;
    private ?string $runtimeInSeconds;
    private ?int $errorCount;
    private string $filters;
    private string $dueAt;
    private ?string $startedAt;
    private ?string $finishedAt;

    public function __construct(array $serviceLogData)
    {
        $this->id = $serviceLogData['id'];
        $this->serviceId = $serviceLogData['service_id'];
        $this->fromFactory = $serviceLogData['from_factory'];
        $this->fromEnvironment = $serviceLogData['from_environment'];
        $this->toFactory = $serviceLogData['to_factory'];
        $this->toEnvironment = $serviceLogData['to_environment'];
        $this->username = $serviceLogData['username'];
        $this->requestedBy = $serviceLogData['requested_by'];
        $this->status = $serviceLogData['status'];
        $this->runtimeInSeconds = $serviceLogData['runtime'];
        $this->errorCount = $serviceLogData['error'];
        $this->filters = $serviceLogData['filters'];
        $this->dueAt = $serviceLogData['due_at'];
        $this->startedAt = $serviceLogData['started_at'];
        $this->finishedAt = $serviceLogData['finished_at'];
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

    public function getServiceId(): int
    {
        return $this->serviceId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getFromFactory(): string
    {
        return $this->fromFactory;
    }

    public function getFromEnvironment(): string
    {
        return $this->fromEnvironment;
    }

    public function getToFactory(): string
    {
        return $this->toFactory;
    }

    public function getToEnvironment(): string
    {
        return $this->toEnvironment;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRequestedBy(): string
    {
        return $this->requestedBy;
    }

    public function getRuntime(): ?string
    {
        return $this->runtimeInSeconds;
    }

    public function getErrorCount(): ?int
    {
        return $this->errorCount;
    }

    public function getFilters(): string
    {
        return $this->filters;
    }

    public function getDueAt(): string
    {
        return $this->dueAt;
    }

    public function getStartedAt(): ?string
    {
        return $this->startedAt;
    }

    public function getFinishedAt(): ?string
    {
        return $this->finishedAt;
    }
}
