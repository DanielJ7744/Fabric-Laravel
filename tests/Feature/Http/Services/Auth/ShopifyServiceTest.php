<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Facades\SystemAuth;
use App\Http\Services\Auth\ShopifyService;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group connectors
 */
class ShopifyServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['factory_name' => 'Shopify']);
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make([
            'name' => 'Test integration UK',
            'username' => 'table'
        ]));
    }

    public function test_credentials_validation(): void
    {
        $data = [
            'credentials' => [''],
            'environment' => 'test',
            'connectorName' => 'test',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'integration_id' => $this->integration->id,
            'system_id' => $this->system->id,
        ];

        $this
            ->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['credentials' => implode(' ', [
                'The store field is required.',
                'The private app field is required unless public app is in true.',
                'The public app field is required unless private app is in true.'
            ])]);

        $data['credentials'] = [
            'public_app' => true,
            'store' => 'test2.myshopify.com',
            'api_key' => null,
            'password' => null,
            'shared_secret' => null,
        ];

        $this
            ->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['credentials' => implode(' ', [
                'The access token field is required when public app is true.'
            ])]);

        $data['credentials'] = [
            'private_app' => true,
            'store' => 'test2.myshopify.com',
            'access_token' => null,
        ];

        $this
            ->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['credentials' => implode(' ', [
                'The api key field is required when private app is true.',
                'The password field is required when private app is true.',
                'The shared secret field is required when private app is true.',
            ])]);
    }

    public function test_store_url_validation(): void
    {
        $data = [
            'credentials' => [
                'public_app' => true,
                'access_token' => '123',
                'store' => 'test2.myshwdwdwdwdwopify.com',
            ],
            'connectorName' => 'test',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'environment' => 'test',
            'integration_id' => $this->integration->id,
            'system_id' => $this->system->id,
        ];

        $this
            ->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['credentials' => implode(' ', [
                'The store must be a myshopify.com domain.',
            ])]);
    }

    public function test_authenticate_method(): void
    {
        $credentials = [
            'public_app' => true,
            'access_token' => 'test_access_token',
            'store' => 'test.myshopify.com',
            'connector_name' => 'test',
            'timezone' => 'UTC',
            'date_format' => 'd/m/Y',
            'authorisation_type' => 'none',
        ];

        $data = [
            'credentials' => $credentials,
            'environment' => 'test',
            'connectorName' => 'test',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'integration_id' => $this->integration->id,
            'system_id' => $this->system->id,
            'authorisation_type' => 'none',
        ];

        $mock = $this->partialMock(ShopifyService::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['access_scopes' => ['scope']]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with('Shopify', $credentials)
            ->andReturn($mock);

        $this->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertCreated();
    }
}
