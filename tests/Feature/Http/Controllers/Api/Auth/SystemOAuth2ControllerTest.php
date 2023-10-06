<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use App\Models\Fabric\AuthorisationType;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

/**
 * @group auth
 */
class SystemOAuth2ControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();


        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make([
            'name' => 'Test integration UK',
            'username' => 'table'
        ]));
    }

    public function test_validation(): void
    {
        $this->passportAs($this->user)
            ->postJson('api/v2/oauth-2', [])
            ->assertJsonValidationErrors([
                'environment' => 'The environment field is required.',
                'timeZone' => 'The time zone field is required.',
                'dateFormat' => 'The date format field is required.',
                'connectorName' => 'The connector name field is required.',
                'system_id' => 'The system id field is required.',
                'integration_id' => 'The integration id field is required.',
            ]);
    }

    public function test_supported_system_validation(): void
    {
        factory(AuthorisationType::class)->create(['name' => 'oauth2']);
        $system = factory(System::class)->create(['factory_name' => 'FakeSystem']);

        $this->passportAs($this->user)
            ->postJson('api/v2/oauth-2', [
                'environment'    => 'test',
                'timeZone'       => 'UTC',
                'dateFormat'     => 'c',
                'connectorName'  => 'test',
                'system_id'      => $system->id,
                'integration_id' => $this->integration->id,
            ])
            ->assertJsonValidationErrors([
                'system_id' => 'The system id must support oauth2 authorisation.',
            ]);
    }
}
