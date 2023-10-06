<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\SystemType;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class SystemTypeControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->systemType = SystemType::first();
        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->patchworks = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_users_can_retrieve_system_types(): void
    {
        $this
            ->passportAs($this->user)
            ->getJson(route('api.v2.system-types.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->systemType->id);
    }

    public function test_users_can_retrieve_a_system_type(): void
    {
        $this
            ->passportAs($this->user)
            ->getJson(route('api.v2.system-types.show', $this->systemType))
            ->assertOk()
            ->assertJsonPath('data.id', $this->systemType->getKey());
    }

    public function test_patchworks_can_create_system_types(): void
    {
        $this
            ->passportAs($this->patchworks)
            ->postJson(
                route('api.v2.system-types.store'),
                factory(SystemType::class)->raw(['name' => 'Unique System Type'])
            )
            ->assertCreated();
    }

    public function test_users_cannot_create_system_types(): void
    {
        $this
            ->passportAs($this->user)
            ->postJson(
                route('api.v2.system-types.store'),
                factory(SystemType::class)->raw(['name' => 'Unique System Type'])
            )
            ->assertForbidden();
    }

    public function test_patchworks_can_update_a_system_type(): void
    {
        $this
            ->passportAs($this->patchworks)
            ->putJson(
                route('api.v2.system-types.update', $this->systemType),
                factory(SystemType::class)->raw(['name' => 'Unique System Type'])
            )->assertOk();
    }

    public function test_users_cannot_update_a_system_type(): void
    {
        $this
            ->passportAs($this->user)
            ->putJson(
                route('api.v2.system-types.update', $this->systemType),
                factory(SystemType::class)->raw(['name' => 'Unique System Type'])
            )->assertForbidden();
    }
}
