<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class EntityControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->secondaryCompany = factory(Company::class)->create(['name' => 'Test Company Secondary']);
        $this->secondaryIntegration = $this->secondaryCompany->integrations()->save(factory(Integration::class)->make());
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->secondaryUser = $this->secondaryCompany->users()->save(factory(User::class)->states('client user')->make());
        $this->globalEntity = factory(Entity::class)->create(['name' => 'Global Entity', 'integration_id' => null]);
        $this->integrationEntity = factory(Entity::class)->create(['name' => 'Integration Entity', 'integration_id' => $this->integration->id]);
    }

    public function test_users_with_permissions_can_retrieve_entities(): void
    {
        $this
            ->passportAs($this->user)
            ->getJson(route('api.v2.entities.index'))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_entities(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.entities.index'))
            ->assertForbidden();
    }

    public function test_users_with_permissions_can_retrieve_a_global_entity(): void
    {
        $this
            ->passportAs($this->user)
            ->getJson(route('api.v2.entities.show', $this->globalEntity))
            ->assertOk();
    }

    public function test_user_with_permission_cannot_retrieve_entity_for_non_owned_integration(): void
    {
        $this
            ->passportAs($this->secondaryUser)
            ->getJson(route('api.v2.entities.show', $this->integrationEntity))
            ->assertNotFound();
    }

    public function test_user_without_permission_cannot_retrieve_an_entity(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.entities.show', $this->globalEntity))
            ->assertForbidden();
    }
}
