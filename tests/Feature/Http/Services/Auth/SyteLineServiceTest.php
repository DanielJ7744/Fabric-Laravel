<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Enums\Systems;
use App\Http\Services\Auth\SyteLineService;

class SyteLineServiceTest extends SFTPServiceTest
{
    protected string $driver = Systems::SYTELINE;
    protected string $serviceClass = SyteLineService::class;
}
