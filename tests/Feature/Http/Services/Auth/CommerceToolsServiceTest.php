<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Facades\SystemAuth;
use App\Http\Services\Auth\CommerceToolsService;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group connectors
 */
class CommerceToolsServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->system = factory(System::class)->create(['factory_name' => 'CommerceTools']);
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
                'The project key field is required.',
                'The client id field is required.',
                'The secret field is required.',
                'The scope field is required.',
                'The api url field is required.',
                'The auth url field is required.',
            ])]);
    }

    public function test_authenticate_method(): void
    {
        $credentials = [
            'project_key' => 'test_project_key',
            'client_id' => 'test_client_id',
            'secret' => 'test_secret',
            'scope' => 'test_scope',
            'api_url'  => 'https://api.us-central1.gcp.commercetools.com',
            'auth_url' => 'https://auth.us-central1.gcp.commercetools.com',
            'connector_name' => 'test',
            'timezone' => 'UTC',
            'date_format' => 'd/m/Y',
            'authorisation_type' => 'none',
        ];

        $data = [
            'credentials' => $credentials,
            'authorisation_type' => 'none',
            'environment' => 'test',
            'connectorName' => 'test',
            'timeZone' => 'UTC',
            'dateFormat' => 'd/m/Y',
            'integration_id' => $this->integration->id,
            'system_id' => $this->system->id,
        ];

        $mock = $this->partialMock(CommerceToolsService::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['access_token' => 'token', 'expires_in' => 3600]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with('CommerceTools', $credentials)
            ->andReturn($mock);

        $this->passportAs($this->user)->postJson(route('api.v2.connectors.store'), $data)->assertCreated();
    }
}
