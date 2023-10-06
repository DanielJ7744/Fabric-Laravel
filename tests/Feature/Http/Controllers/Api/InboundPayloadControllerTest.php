<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Facades\Hasura;
use App\Models\Fabric\Company;
use App\Models\Fabric\InboundEndpoint;
use App\Models\Fabric\Integration;
use App\Models\Fabric\OauthClient;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use App\Models\Tapestry\Connector;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\LaravelTestCase;

/**
 * @group hasura
 * @group inbound
 */
class InboundPayloadControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->system = factory(System::class)->create(['factory_name' => 'InboundAPI']);
        $this->service = $this->integration->services()->save(factory(Service::class)->make(['from_factory' => 'InboundAPI\\Pull\\JsonPayload', 'username' => 'table']));
        $this->connector = tap(factory(Connector::class)->make(['system_chain' => 'InboundAPI', 'common_ref' => $this->service->from_environment])->setIdxTable($this->integration->username))->save();
        $this->endpoint = $this->integration->endpoints()->save(factory(InboundEndpoint::class)->make(['service_id' => $this->service->id]));
    }

    public function test_oauth_client_credential_tokens_can_post_a_payload(): void
    {
        $client = $this->connector->clients()->save(factory(OauthClient::class)->make(), ['safe_secret' => 'secret']);
        $payload = ['test' => true];

        $response = $this
            ->postJson(route('passport.token'), [
                'grant_type' => 'client_credentials',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'scope' => 'access-inbound',
            ]);

        Hasura::shouldReceive('storePayload')
            ->with(
                Mockery::on(fn ($endpoint): bool => $endpoint->is($this->endpoint)),
                $payload
            )
            ->once()
            ->andReturn((object) ['id' => 1]);

        $this
            ->withHeaders(['Authorization' => sprintf('Bearer %s', $response->json('access_token'))])
            ->postJson(route('api.v1.inbound.payload.store', [
                'integration_slug' => $this->integration->slug,
                'endpoint_slug' => $this->endpoint->slug,
            ]), $payload)
            ->assertOk()
            ->assertJsonPath('message', 'Payload stored successfully.');
    }
}
