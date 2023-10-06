<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Enums\Systems;
use App\Http\Services\Auth\CsvService;

class CsvServiceTest extends SFTPServiceTest
{
    protected string $driver = Systems::CSV;
    protected string $serviceClass = CsvService::class;
}
