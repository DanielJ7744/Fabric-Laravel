<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class SystemControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->system = factory(System::class)->create();
        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_users_can_retrieve_systems(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.systems.index'))
            ->assertOk();
    }

    public function test_users_can_retrieve_a_system(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.systems.show', $this->system))
            ->assertOk();
    }
}
