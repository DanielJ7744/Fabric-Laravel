<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Facades\Hasura;
use App\Models\Fabric\Company;
use App\Models\Fabric\InboundEndpoint;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery;
use Tests\LaravelTestCase;

/**
 * @group hasura
 */
class InboundEndpointControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->service = $this->integration->services()->save(factory(Service::class)->make());
        $this->endpoint = $this->integration->endpoints()->save(factory(InboundEndpoint::class)->make(['service_id' => $this->service->id]));
    }

    public function test_user_with_permission_can_retrieve_inbound_endpoints(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.inbound-endpoints.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->endpoint->id);
    }

    public function test_user_without_permission_cannot_retrieve_inbound_endpoints(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.inbound-endpoints.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_an_inbound_endpoint(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.inbound-endpoints.show', $this->endpoint))
            ->assertOk()
            ->assertJsonPath('data.id', $this->endpoint->getKey());
    }

    public function test_user_without_permission_cannot_retrieve_an_inbound_endpoint(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.inbound-endpoints.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_create_inbound_endpoints(): void
    {
        $service = $this->integration->services()->save(factory(Service::class)->make());
        $attributes = factory(InboundEndpoint::class)->raw([
            'service_id' => $service->id,
            'integration_id' => $this->integration->id,
            'slug' => Str::slug($this->integration->name)
        ]);
        $endpoint = ['id' => $attributes['external_endpoint_id']];

        Hasura::shouldReceive('createEndpoint')
            ->with(
                Mockery::on(fn ($company): bool => $company->is($this->company)),
                $attributes['slug'],
                $this->company->name
            )
            ->once()
            ->andReturn((object) $endpoint);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.inbound-endpoints.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new InboundEndpoint)->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_inbound_endpoints(): void
    {
        $attributes = factory(InboundEndpoint::class)->raw();

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.inbound-endpoints.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_an_inbound_endpoint(): void
    {
        $attributes = factory(InboundEndpoint::class)->raw();

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.inbound-endpoints.update', $this->endpoint), $attributes)
            ->assertOk();
    }

    public function test_user_without_permission_cannot_update_an_inbound_endpoint(): void
    {
        $attributes = factory(InboundEndpoint::class)->raw();

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.inbound-endpoints.update', $this->endpoint), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_an_inbound_endpoint(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.inbound-endpoints.destroy', $this->endpoint))
            ->assertOk()
            ->assertJsonPath('message', 'Endpoint deleted successfully.');

        $this->assertDeleted($this->endpoint);
    }

    public function test_user_without_permission_cannot_delete_an_inbound_endpoint(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.inbound-endpoints.destroy', $this->endpoint))
            ->assertForbidden();
    }
}
