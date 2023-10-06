<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Facades\SystemAuth;
use App\Http\Services\Auth\ZigZagService;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group connectors
 */
class ZigZagServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['factory_name' => 'ZigZag']);
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
                'The username field is required.',
                'The password field is required.',
                'The url field is required.',
            ])]);
    }

    public function test_authenticate_method(): void
    {
        $credentials = [
            'url' => 'https://test_url.com',
            'username' => 'test_username',
            'password' => 'test_password',
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

        $mock = $this->partialMock(ZigZagService::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['status_code' => 200]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with('ZigZag', $credentials)
            ->andReturn($mock);

        $this
            ->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertCreated();
    }
}
