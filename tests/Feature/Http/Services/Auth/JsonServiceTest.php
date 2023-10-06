<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Enums\Systems;
use App\Http\Services\Auth\JsonService;

class JsonServiceTest extends SFTPServiceTest
{
    protected string $driver = Systems::JSON;
    protected string $serviceClass = JsonService::class;
}
