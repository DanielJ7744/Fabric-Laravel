<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Facades\SystemAuth;
use App\Http\Services\Auth\S3Service;
use Tests\LaravelTestCase;
use App\Models\Fabric\User;
use App\Models\Fabric\System;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class S3ServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Integration $integration;
    protected System $system;
    protected Company $company;

    public function setup(): void
    {
        parent::setUp();
        $this->company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['factory_name' => 'S3']);
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
                'The key field is required.',
                'The secret field is required.',
            ])]);
    }

    public function test_authenticate_method(): void
    {
        $credentials = [
            'key' => 'test',
            'secret' => 'test',
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

        $s3Mock = $this->partialMock(S3Service::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['statusCode' => 200]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with('S3', $credentials)
            ->andReturn($s3Mock);

        $this->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertCreated();
    }
}
