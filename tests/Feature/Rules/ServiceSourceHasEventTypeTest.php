<?php

namespace Tests\Feature\Rules;

use App\Models\Fabric\Company;
use App\Models\Fabric\EventType;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use App\Models\Tapestry\Service;
use App\Rules\ServiceSourceHasEventType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class ServiceSourceHasEventTypeTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $company = factory(Company::class)->create();
        $this->validSystem = factory(System::class)->create(['factory_name' => 'Shopify']);
        $this->invalidSystem = factory(System::class)->create(['factory_name' => 'Peoplevox']);
        $this->clientUser = $company->users()->save(factory(User::class)->states('client user')->make());
        $this->integration = $company->integrations()->save(factory(Integration::class)->make(['username' => 'patchworks']));
        $this->service = $this->integration->services()->save(factory(Service::class)->make([
            'from_factory' => 'Shopify\\Pull\\Orders',
        ]));
        $this->validEventType = factory(EventType::class)->create([
            'system_id' => $this->validSystem->id
        ]);
        $this->invalidEventType = factory(EventType::class)->create([
            'system_id' => $this->invalidSystem->id
        ]);
        $this->rule = new ServiceSourceHasEventType($this->service->id);
    }

    public function test_valid_event_id_passes()
    {
        $this->passportAs($this->clientUser)->assertTrue($this->rule->passes('event_type_id', $this->validEventType->id));
    }

    public function test_invalid_event_id_fails()
    {
        $this->passportAs($this->clientUser)->assertFalse($this->rule->passes('event_type_id', $this->invalidEventType->id));
    }
}
