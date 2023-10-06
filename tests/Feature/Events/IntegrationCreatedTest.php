<?php

namespace Tests\Feature\Events;

use App\Events\IntegrationCreated;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\LaravelTestCase;

class IntegrationCreatedTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
    }

    public function test_generateIdxTable_despatches_event(): void
    {
        Event::fake([IntegrationCreated::class]);
        $this->integration->generateIdxTable();
        Event::assertDispatched(IntegrationCreated::class);
    }
}
