<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\LaravelTestCase;
use App\Models\Fabric\User;
use App\Models\Fabric\System;
use App\Models\Fabric\Company;
use App\Models\Fabric\AuthorisationType;
use App\Models\Fabric\SystemAuthorisationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSystemAuthorisationTypeControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['name' => 'Shopify']);
        $this->authorisationType = factory(AuthorisationType::class)->create(['name' => 'Test']);

        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->states('patchworks user')->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_create_a_system_authorisation_type(): void
    {
        $attributes = factory(SystemAuthorisationType::class)->raw([
            'authorisation_type_id' => $this->authorisationType->id,
            'system_id' => $this->system->id
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.system-authorisation-types.store'), $attributes)
            ->assertCreated();

        $this->assertDatabaseHas((new SystemAuthorisationType())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_a_system_authorisation_type(): void
    {
        $attributes = factory(SystemAuthorisationType::class)->raw([
            'authorisation_type_id' => $this->authorisationType->id,
            'system_id' => $this->system->id
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.system-authorisation-types.store'), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_a_system_authorisation_type(): void
    {
        $systemAuthorisationType = factory(SystemAuthorisationType::class)->create([
            'authorisation_type_id' => $this->authorisationType->id,
            'system_id' => $this->system->id
        ]);

        $attributes = [
            'credentials_schema' => '{"attributes":{"test":"test"}}'
        ];

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.system-authorisation-types.update', $systemAuthorisationType), $attributes)
            ->assertOk();

        $this->assertSame($attributes['credentials_schema'], $systemAuthorisationType->fresh()->credentials_schema);
    }

    public function test_user_without_permission_cannot_update_a_system_authorisation_type(): void
    {
        $systemAuthorisationType = factory(SystemAuthorisationType::class)->create([
            'authorisation_type_id' => $this->authorisationType->id,
            'system_id' => $this->system->id
        ]);

        $attributes = [
            'credentials_schema' => '{"attributes":{"test":"test"}}'
        ];

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.system-authorisation-types.update', $systemAuthorisationType), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_system_authorisation_type(): void
    {
        $systemAuthorisationType = factory(SystemAuthorisationType::class)->create([
            'authorisation_type_id' => $this->authorisationType->id,
            'system_id' => $this->system->id
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.system-authorisation-types.destroy', $systemAuthorisationType))
            ->assertOk()
            ->assertJsonPath('message', 'System authorisation type deleted successfully.');

        $this->assertDatabaseMissing($systemAuthorisationType->getTable(), $systemAuthorisationType->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_system_authorisation_type(): void
    {
        $systemAuthorisationType = factory(SystemAuthorisationType::class)->create([
            'authorisation_type_id' => $this->authorisationType->id,
            'system_id' => $this->system->id
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.system-authorisation-types.destroy', $systemAuthorisationType))
            ->assertForbidden();
    }
}
