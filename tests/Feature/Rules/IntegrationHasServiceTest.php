<?php

namespace Tests\Feature\Rules;

use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use App\Models\Tapestry\Service;
use App\Rules\IntegrationHasService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class IntegrationHasServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new IntegrationHasService;
        $company = factory(Company::class)->create();
        $this->clientUser = $company->users()->save(factory(User::class)->states('client user')->make());
        $this->integration = $company->integrations()->save(factory(Integration::class)->make(['username' => 'patchworks']));
        $this->validService = $this->integration->services()->save(factory(Service::class)->make());
        $this->invalidService = factory(Service::class)->make([
            'username' => 'juno',
        ]);
    }

    public function test_valid_service_id_passes()
    {
        $this->passportAs($this->clientUser)->assertTrue($this->rule->passes('service_id', $this->validService->id));
    }

    public function test_invalid_service_id_fails()
    {
        $this->passportAs($this->clientUser)->assertFalse($this->rule->passes('service_id', $this->invalidService->id));
    }
}
