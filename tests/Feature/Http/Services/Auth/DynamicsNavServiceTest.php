<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Facades\SystemAuth;
use App\Http\Services\Auth\DynamicsNavService;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group connectors
 */
class DynamicsNavServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['factory_name' => 'DynamicsNav']);
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
            ->assertJsonValidationErrors([
                'credentials' => implode(
                    ' ',
                    [
                        'The type field is required.',
                        'The server instance field is required.',
                        'The username field is required.',
                        'The password field is required.',
                        'The company field is required.',
                        'The url field is required.'
                    ]
                ),
                'authorisation_type' => 'The authorisation type field is required.'
            ]);
    }

    public function test_ntlm_requires_domain(): void
    {
        $data = [
            'credentials' => [
                'type' => 'ntlm',
                'server_instance' => 'test_server_instance',
                'username' => 'test_username',
                'password' => 'test_password',
                'company' => 'test_company',
                'url' => 'https://test.pwks.co',
                'authorisation_type' => 'none'
            ],
            'connectorName' => 'test',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'environment' => 'test',
            'integration_id' => $this->integration->id,
            'system_id' => $this->system->id,
            'authorisation_type' => 'none'
        ];

        $this
            ->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['credentials' => implode(' ', [
                'The domain field is required when type is ntlm.',
            ])]);
    }

    public function test_authenticate_method(): void
    {
        $credentials = [
            'server_instance' => 'test_server_instance',
            'username' => 'test_username',
            'password' => 'test_password',
            'company' => 'test_company',
            'url' => 'https://test.pwks.co',
            'type' => 'ntlm',
            'domain' => 'http://test.pwks.co',
            'connector_name' => 'test',
            'timezone' => 'UTC',
            'date_format' => 'd/m/Y',
            'authorisation_type' => 'none',
        ];

        $data = [
            'credentials' => $credentials,
            'connectorName' => 'test',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'environment' => 'test',
            'integration_id' => $this->integration->id,
            'system_id' => $this->system->id,
            'authorisation_type' => 'none',
        ];

        $mock = $this->partialMock(DynamicsNavService::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['contractRef' => ['test']]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with('DynamicsNav', $credentials)
            ->andReturn($mock);

        $this->passportAs($this->user)->postJson(route('api.v2.connectors.store'), $data)->assertCreated();
    }
}
