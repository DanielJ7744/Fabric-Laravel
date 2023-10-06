<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Facades\SystemAuth;
use App\Http\Services\Auth\SapB1Service;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group connectors
 */
class SapB1ServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['factory_name' => 'SapB1']);
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make([
            'name' => 'Test integration UK',
            'username' => 'table'
        ]));
    }

    public function test_credentials_validation(): void
    {
        $data = [
            'credentials' => [
                'test' => 'test'
            ],
            'environment' => 'test',
            'connectorName' => 'SapB1',
            'timeZone' => 'UTC',
            'dateFormat' => 'Y-m-d',
            'integration_id' => $this->integration->id,
            'system_id' => $this->system->id,
        ];

        $this->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['credentials' => implode(' ', [
                'The api url field is required.',
                'The api user field is required.',
                'The api password field is required.',
                'The sap db field is required.',
            ])]);
    }

    public function test_authenticate_method(): void
    {
        $credentials = [
            'api_url' => 'https://localhost/',
            'api_user' => 'test',
            'api_password' => 'test',
            'sap_db' => 'test',
            'connector_name' => 'SapB1',
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d',
            'authorisation_type' => 'none',
        ];

        $data = [
            'credentials' => $credentials,
            'environment' => 'test',
            'connectorName' => 'SapB1',
            'timeZone' => 'UTC',
            'dateFormat' => 'Y-m-d',
            'integration_id' => $this->integration->id,
            'system_id' => $this->system->id,
            'authorisation_type' => 'none',
        ];

        $mock = $this->partialMock(SapB1Service::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['SessionId' => '']));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with('SapB1', $credentials)
            ->andReturn($mock);

        $this->passportAs($this->user)->postJson(route('api.v2.connectors.store'), $data)->assertCreated();
    }
}
