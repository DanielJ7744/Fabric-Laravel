<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\EventType;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class EventTypeControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $company = factory(Company::class)->create();
        $this->user = $company->users()->save(factory(User::class)->make());
        $this->clientUser = $company->users()->save(factory(User::class)->states('client user')->make());
        $this->eventType = factory(EventType::class)->create();
    }

    public function test_can_retrieve_event_types_with_permission(): void
    {
        $this->passportAs($this->clientUser)
            ->getJson(route('api.v2.event-types.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->eventType->id);
    }

    public function test_can_retrieve_an_event_type_with_permission(): void
    {
        $this->passportAs($this->clientUser)
            ->getJson(route('api.v2.event-types.show', $this->eventType->id))
            ->assertOk();
    }

    public function test_cannot_retrieve_event_types_without_permission(): void
    {
        $this->passportAs($this->user)
            ->getJson(route('api.v2.event-types.index'))
            ->assertForbidden();
    }

    public function test_cannot_retrieve_an_event_type_without_permission(): void
    {
        $this->passportAs($this->user)
            ->getJson(route('api.v2.event-types.show', $this->eventType->id))
            ->assertForbidden();
    }
}
