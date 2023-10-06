<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Facades\SystemAuth;
use App\Http\Services\Auth\ReboundService;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group connectors
 */
class ReboundServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['factory_name' => 'Rebound']);
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

        $this->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['credentials' => implode(' ', [
                'The login field is required.',
                'The api key field is required.',
                'The url field is required.',
            ])]);
    }

    public function test_url_https_validation(): void
    {
        $data = [
            'credentials' => [
                'login' => 'test_login',
                'api_key' => 'test_api_key',
                'url' => 'http://intelligentreturns.net'
            ],
            'connectorName' => 'test',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'environment' => 'test',
            'integration_id' => $this->integration->id,
            'system_id' => $this->system->id,
        ];

        $this->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['credentials' => implode(' ', [
                'The url must use HTTPS.',
            ])]);
    }

    public function test_authenticate_method(): void
    {
        $credentials = [
            'login' => 'test_login',
            'api_key' => 'test_api_key',
            'url' => 'https://test.intelligentreturns.net',
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

        $mock = $this->partialMock(ReboundService::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['success' => true]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with('Rebound', $credentials)
            ->andReturn($mock);

        $this->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertCreated();
    }
}
