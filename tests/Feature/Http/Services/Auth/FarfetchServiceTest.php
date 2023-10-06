<?php

namespace Tests\Feature\Http\Services\Auth;

use App\Facades\SystemAuth;
use App\Http\Services\Auth\FarfetchService;
use App\Http\Services\Auth\PeoplevoxService;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group connectors
 */
class FarfetchServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['factory_name' => 'Farfetch']);
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
                'The retail url field is required.',
                'The sales url field is required.',
                'The key field is required.',
                'The store id field is required.',
                'The large box id field is required.',
                'The null box id field is required.',
            ])]);
    }

    public function test_authenticate_method(): void
    {
        $credentials = [
            'key' => 'test_client_id',
            'store_id' => 'test_store_id',
            'large_box_id' => 'test_box_id',
            'null_box_id' => 'test_box_id',
            'retail_url' => 'https://www.pwk.co',
            'sales_url' => 'https://www.pwk.co',
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

        $mock = $this->partialMock(FarfetchService::class, fn ($mock) => $mock
            ->shouldReceive('authenticate')
            ->andReturn(['success'=> true]));

        SystemAuth::partialMock()
            ->shouldReceive('driver')
            ->once()
            ->with('Farfetch', $credentials)
            ->andReturn($mock);

        $this->passportAs($this->user)
            ->postJson(route('api.v2.connectors.store'), $data)
            ->assertCreated();
    }
}
