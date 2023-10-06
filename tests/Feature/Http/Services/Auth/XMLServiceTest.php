<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Enums\Systems;
use App\Http\Services\Auth\XmlService;

class XMLServiceTest extends SFTPServiceTest
{
    protected string $driver = Systems::XML;
    protected string $serviceClass = XmlService::class;
}
