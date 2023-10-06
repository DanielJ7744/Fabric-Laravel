<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FilterTemplate;
use App\Models\Fabric\ServiceOption;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class ServiceOptionControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->serviceOption = factory(ServiceOption::class)->create();
    }

    public function test_user_with_permission_can_retrieve_service_options(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.service-options.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_service_options(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.service-options.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_service_option(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.service-options.show', $this->serviceOption))
            ->assertOk()
            ->assertJsonPath('data.id', $this->serviceOption->id);
    }

    public function test_user_without_permission_cannot_retrieve_a_service_option(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.service-options.show', $this->serviceOption))
            ->assertForbidden();
    }
}
