<?php

namespace App\Models\Fabric;

use Illuminate\Contracts\Routing\UrlRoutable;

class Mapping implements UrlRoutable
{
    private string $id;
    private string $data;
    private string $createdAt;

    public function __construct(string $id, string $data, string $createdAt)
    {
        $this->id = $id;
        $this->data = $data;
        $this->createdAt = $createdAt;
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

    public function getData(): string
    {
        return $this->data;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
