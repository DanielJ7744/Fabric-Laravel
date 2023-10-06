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

class AdminServiceOptionControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->serviceOption = factory(ServiceOption::class)->create(['key' => 'test_option']);
    }

    public function test_user_with_permission_can_create_service_options(): void
    {
        $attributes = factory(ServiceOption::class)->raw();

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.service-options.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new ServiceOption())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_service_options(): void
    {
        $attributes = factory(ServiceOption::class)->raw();

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.service-options.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_service_options(): void
    {
        $attributes = factory(ServiceOption::class)->raw(['key' => 'new_key']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.service-options.update', $this->serviceOption), $attributes)
            ->assertOk();

        $this->assertSame($attributes['key'], $this->serviceOption->fresh()->key);
    }

    public function test_user_without_permission_cannot_update_service_options(): void
    {
        $attributes = factory(ServiceOption::class)->raw(['key' => 'new_key']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.service-options.update', $this->serviceOption), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_service_option(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->delete(route('api.v2.admin.service-options.destroy', $this->serviceOption))
            ->assertOk()
            ->assertJsonPath('message', 'Service option deleted successfully.');

        $this->assertDatabaseMissing($this->serviceOption->getTable(), $this->serviceOption->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_service_option(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->delete(route('api.v2.admin.service-options.destroy', $this->serviceOption))
            ->assertForbidden();
    }
}
