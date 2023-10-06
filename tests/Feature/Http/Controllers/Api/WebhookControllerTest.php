<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\EventType;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use App\Models\Fabric\Webhook;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class WebhookControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    /**
     * Store/update methods are currently tested in tests/Feature/Http/Services/Webhook
     */

    public function setUp(): void
    {
        parent::setUp();
        $company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['factory_name' => 'Shopify']);
        $this->user = $company->users()->save(factory(User::class)->make());
        $this->clientUser = $company->users()->save(factory(User::class)->states('client user')->make());
        $this->clientAdmin = $company->users()->save(factory(User::class)->states('client admin')->make());
        $this->integration = $company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->service = $this->integration->services()->save(factory(Service::class)->make([
            'username' => $this->integration->username,
            'from_factory' => 'Shopify\\Pull\\Orders',
        ]));
        $this->eventType = factory(EventType::class)->create([
            'system_id' => $this->system->id
        ]);
        $this->webhook = factory(Webhook::class)->create([
            'integration_id' => $this->integration->id,
            'service_id' => $this->service->id,
            'event_type_id' => $this->eventType
        ]);
    }

    public function test_can_retrieve_webhooks_with_permission(): void
    {
        $this->passportAs($this->clientUser)
            ->getJson(route('api.v2.webhooks.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->webhook->id);
    }

    public function test_can_retrieve_a_webhook_with_permission(): void
    {
        $this->passportAs($this->clientUser)
            ->getJson(route('api.v2.webhooks.show', $this->webhook->id))
            ->assertOk();
    }

    public function test_cannot_retrieve_webhooks_without_permission(): void
    {
        $this->passportAs($this->user)
            ->getJson(route('api.v2.webhooks.index'))
            ->assertForbidden();
    }

    public function test_cannot_retrieve_a_webhook_without_permission(): void
    {
        $this->passportAs($this->user)
            ->getJson(route('api.v2.webhooks.show', $this->webhook->id))
            ->assertForbidden();
    }

    public function test_cannot_update_without_permission(): void
    {
        $this->passportAs($this->user)
            ->patchJson(route('api.v2.webhooks.update', $this->webhook->id), [
                'active' => false,
            ])
            ->assertForbidden();
    }

    public function test_can_update_with_permission(): void
    {
        $this->passportAs($this->clientAdmin)
            ->patchJson(route('api.v2.webhooks.update', $this->webhook->id), [
                'active' => false,
            ])
            ->assertStatus(200);
    }

    public function test_cannot_create_without_permission(): void
    {
        $this->passportAs($this->user)
            ->postJson(route('api.v2.webhooks.store'), [
                'service_id' => $this->service->id,
                'event_type_id' => $this->eventType->id
            ])
            ->assertForbidden();
    }

    public function test_cannot_delete_without_permission(): void
    {
        $this->passportAs($this->clientUser)
            ->deleteJson(route('api.v2.webhooks.destroy', $this->webhook->id))
            ->assertForbidden();
    }
}
