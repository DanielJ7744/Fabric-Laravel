<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\System;
use App\Models\Fabric\SystemType;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\LaravelTestCase;

class AdminSystemControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->system = factory(System::class)->create();
        $this->systemType = factory(SystemType::class)->create(['name' => 'Test System Type']);
    }

    public function test_user_with_permission_can_create_systems(): void
    {
        Storage::fake('images');

        $attributes = factory(System::class)->raw([
            'name' => 'Shopify',
            'system_type_id' => $this->systemType->id,
            'image' => UploadedFile::fake()->image('test-image.svg')
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.systems.store'), $attributes)
            ->assertCreated();

        // the image isn't returned with the model, hence unsetting
        unset($attributes['image']);

        $this->assertDatabaseHas((new System())->getTable(), $attributes);
    }

    public function test_user_with_permission_cannot_create_invalid_system(): void
    {
        Storage::fake('images');

        $attributes = factory(System::class)->raw([
            'name' => 'Test',
            'system_type_id' => $this->systemType->id,
            'image' => UploadedFile::fake()->image('test-image.svg')
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.systems.store'), $attributes)
            ->assertJsonValidationErrors(['name' => 'The system name does not have a driver within the system auth manager.']);
    }

    public function test_user_without_permission_cannot_create_systems(): void
    {
        $attributes = factory(System::class)->raw([
            'name' => 'Test Company Ltd',
            'system_type_id' => $this->systemType->id
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.systems.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_systems(): void
    {
        $system = factory(System::class)->create(['name' => 'Test System']);
        $attributes = factory(System::class)->raw(['name' => 'Updated System']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.systems.update', $system), $attributes)
            ->assertOk();

        $this->assertSame($attributes['name'], $system->fresh()->name);
    }

    public function test_user_without_permission_cannot_update_systems(): void
    {
        $system = factory(System::class)->create(['name' => 'Test System']);
        $attributes = factory(System::class)->raw(['name' => 'Updated System']);

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.systems.update', $system), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_system(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.systems.destroy', $this->system))
            ->assertOk()
            ->assertJsonPath('message', 'System deleted successfully.');

        $this->assertDatabaseMissing($this->system->getTable(), $this->system->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_system(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.systems.destroy', $this->system))
            ->assertForbidden();
    }
}
