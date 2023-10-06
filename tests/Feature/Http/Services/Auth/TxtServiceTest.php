<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Enums\Systems;
use App\Http\Services\Auth\TxtService;

class TxtServiceTest extends SFTPServiceTest
{
    protected string $driver = Systems::TXT;
    protected string $serviceClass = TxtService::class;
}
