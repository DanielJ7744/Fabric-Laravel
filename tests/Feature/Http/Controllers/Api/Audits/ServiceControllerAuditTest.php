<?php

namespace Tests\Feature\Http\Controllers\Api\Audits;

use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class ServiceControllerAuditTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        config(['audit.console' => true]);

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->service = $this->integration->services()->save(factory(Service::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_updating_services_is_audited(): void
    {
        $this->service->update(['status' => false]);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.services.update', $this->service), ['status' => true])
            ->assertOk();

        $this->assertDatabaseHas('audits', [
            'event' => 'updated',
            'auditable_id' => $this->service->getKey(),
            'auditable_type' => Service::class,
            'old_values' => json_encode(['status' => "0"]),
            'new_values' => json_encode(['status' => true]),
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.services.update', $this->service), ['status' => false])
            ->assertOk();

        $this->assertDatabaseHas('audits', [
            'event' => 'updated',
            'auditable_id' => $this->service->getKey(),
            'auditable_type' => Service::class,
            'old_values' => json_encode(['status' => "1"]),
            'new_values' => json_encode(['status' => false]),
        ]);
    }
}
