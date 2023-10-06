<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Enums\Systems;
use App\Facades\SystemAuth;
use App\Http\Services\Auth\SFTPService;
use Tests\LaravelTestCase;
use App\Models\Fabric\User;
use App\Models\Fabric\System;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SFTPServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Integration $integration;
    protected System $system;
    protected Company $company;
    protected string $driver = Systems::SFTP;
    protected string $serviceClass = SFTPService::class;

    public function setup(): void
    {
        parent::setUp();
        $this->company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['factory_name' => $this->driver]);
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make([
            'name' => 'Test integration UK',
            'username' => 'table'
        ]));
    }

    public function test_required_fields(): void
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
                'The host field is required.',
                'The port field is required.',
                'The username field is required.',
                'The password field is required when public key is not present.',
                'The public key field is required when password is not present.'
            ])]);
    }

    public function test_password_authenticate_method(): void
    {
        $credentials = [
            'host' => 'https://sftp.com',
            'port' => 22,
            'username' => 'test',
            'password' => 'test',
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

        $sftpMock = $this->partialMock($this->serviceClass, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['status' => true]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with($this->driver, $credentials)
            ->andReturn($sftpMock);

        $this->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertCreated();
    }

    public function test_ssh_authenticate_method(): void
    {
        $credentials = [
            'host' => 'https://sftp.com',
            'port' => 22,
            'username' => 'test',
            'public_key' => 'test_public_key',
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

        $mock = $this->partialMock($this->serviceClass, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->with()
            ->andReturn(['status' => true]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with($this->driver, $credentials)
            ->andReturn($mock);

        $this->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertCreated();
    }
}
