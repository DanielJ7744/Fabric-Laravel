<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\DefaultPayload;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemSchema;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminFactoryControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('patchworks user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());

        $this->factory = factory(Factory::class)->create(['name' => 'Test Factory']);
    }

    public function test_user_with_permission_can_create_a_factory(): void
    {
        $attributes = factory(Factory::class)->raw([
            'name' => 'New Factory'
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.factories.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new Factory())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_a_factory(): void
    {
        $attributes = factory(Factory::class)->raw([
            'name' => 'New Factory'
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.factories.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_a_factory(): void
    {
        $attributes = factory(Factory::class)->raw([
            'name' => 'New Factory'
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.factories.update', $this->factory), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $this->factory->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_a_factory(): void
    {
        $attributes = factory(Factory::class)->raw([
            'name' => 'New Factory'
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.factories.update', $this->factory), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_factory(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.factories.destroy', $this->factory))
            ->assertOk()
            ->assertJsonPath('message', 'Factory deleted successfully.');

        $this->assertDatabaseMissing($this->factory->getTable(), $this->factory->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_factory(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.factories.destroy', $this->factory))
            ->assertForbidden();
    }
}
