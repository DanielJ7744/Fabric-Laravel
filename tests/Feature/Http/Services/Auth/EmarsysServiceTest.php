<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Facades\SystemAuth;
use App\Http\Services\Auth\EmarsysService;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class EmarsysServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->system = factory(System::class)->create(['factory_name' => 'Emarsys']);
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
                'The username field is required.',
                'The api secret field is required.',
                'The ftp host field is required.',
                'The ftp port field is required.',
                'The ftp username field is required.',
                'The ftp password field is required.',
                'The ftp passive field is required.',
            ])]);
    }

    public function test_authenticate_method(): void
    {
        $credentials = [
            'username' => 'test_username',
            'api_secret' => 'test_secret',
            'ftp_host' => 'test_ftp_host',
            'ftp_port' => '22',
            'ftp_username' => 'test_ftp_username',
            'ftp_password' => 'test_ftp_password',
            'ftp_passive' => '1',
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

        $mock = $this->partialMock(EmarsysService::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['api' => ['replyCode' => 200, 'replyText' => 'OK'], 'ftp' => ['ftpConnected' => true]]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with('Emarsys', $credentials)
            ->andReturn($mock);

        $this->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertCreated();
    }
}
