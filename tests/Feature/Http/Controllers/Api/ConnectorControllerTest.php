<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\Subscription;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use App\Models\Tapestry\Connector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class ConnectorControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    /**
     * Store/update methods are currently tested in tests/Feature/Http/Services/Auth
     */

    public function setUp(): void
    {
        parent::setUp();

        $company = factory(Company::class)->create();
        $baseTierCompany = factory(Company::class)->create();
        $baseSubscription = Subscription::find('name', 'Base');
        $baseTierCompany->subscriptions()->syncWithoutDetaching($baseSubscription);
        $this->baseTierUser = $baseTierCompany->users()->save(factory(User::class)->make());
        $this->user = $company->users()->save(factory(User::class)->make());
        $this->clientUser = $company->users()->save(factory(User::class)->states('client user')->make());
        $this->clientAdmin = $company->users()->save(factory(User::class)->states('client admin')->make());
        $this->integration = $company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->baseTierIntegration = $baseTierCompany->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->system = factory(System::class)->create(['name' => 'Peoplevox', 'factory_name' => 'Peoplevox']);
        $this->connector = tap(factory(Connector::class)->make(['system_chain' => 'Peoplevox'])->setIdxTable($this->integration->username))->save();
    }

    public function test_can_retrieve_connectors_with_permission(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.connectors.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->connector->id);
    }

    public function test_can_retrieve_a_connector_with_permission(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.connectors.show', $this->connector->id))
            ->assertOk();
    }

    public function test_cannot_create_without_permission(): void
    {
        $data = [
            'credentials' => [
                'client_id' => 'pwks1234',
                'username' => 'patchworks',
                'password' => 'pwks$ecure321',
                'url' => 'https://wms.peoplevox.net',
                'authorisation_type' => 'none',
            ],
            'connectorName' => 'test',
            'authorisation_type' => 'none',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'environment' => 'test',
            'integration_id' => $this->integration->id,
            'system_id' => $this->system->id,
        ];

        $this
            ->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertForbidden();
    }

    public function test_cannot_update_without_permission(): void
    {
        $data = [
            'credentials' => [
                'client_id' => 'pwks1234',
                'username' => 'patchworks',
                'password' => 'pwks$ecure321',
                'url' => 'https://wms.peoplevox.net'
            ],
            'environment' => 'test',
        ];

        $this
            ->passportAs($this->user)
            ->putJson(route('api.v2.connectors.update', $this->connector->id), $data)
            ->assertForbidden();
    }

    public function test_can_delete_with_permission(): void
    {
        $this
            ->passportAs($this->clientAdmin)
            ->deleteJson(route('api.v2.connectors.destroy', $this->connector->id))
            ->assertOk();
    }

    public function test_cannot_delete_without_permission(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->deleteJson(route('api.v2.connectors.destroy', $this->connector->id))
            ->assertForbidden();
    }

    public function test_base_tier_user_cannot_create_connector(): void
    {
        $data = [
            'credentials' => [
                'client_id' => 'pwks1234',
                'username' => 'patchworks',
                'password' => 'pwks$ecure321',
                'url' => 'https://wms.peoplevox.net',
                'authorisation_type' => 'none',
            ],
            'connectorName' => 'test',
            'timeZone' => 'UTC',
            'authorisation_type' => 'none',
            'dateFormat' => 'd/m/Y',
            'environment' => 'test',
            'integration_id' => $this->baseTierIntegration->id,
            'system_id' => $this->system->id,
        ];

        $this
            ->passportAs($this->baseTierUser)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertForbidden();
    }

    public function test_can_create_an_inbound_api_connector(): void
    {
        $inboundApiSystem = factory(System::class)->states('inbound-api')->create();

        $payload = [
            'credentials' => [
                'inbound_api_id' => 123,
                'authorisation_type' => 'none',
            ],
            'connectorName' => 'test',
            'authorisation_type' => 'none',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'environment' => 'test',
            'integration_id' => $this->integration->id,
            'system_id' => $inboundApiSystem->id,
        ];

        $this
            ->passportAs($this->clientUser)
            ->postJson(route('api.v2.connectors.store'), $payload)
            ->assertCreated();

        $this->assertDatabaseHas('idx_table', [
            'system_chain' => $inboundApiSystem->factory_name,
        ]);
    }
}
