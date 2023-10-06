<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Factory;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class FactoryControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->factory = factory(Factory::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_user_with_permission_can_retrieve_factories(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.factories.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_factories(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.factories.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_factory(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.factories.show', $this->factory))
            ->assertOk()
            ->assertJsonPath('data.id', $this->factory->id);
    }

    public function test_user_without_permission_cannot_retrieve_a_factory(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.factories.show', $this->factory))
            ->assertForbidden();
    }
}
