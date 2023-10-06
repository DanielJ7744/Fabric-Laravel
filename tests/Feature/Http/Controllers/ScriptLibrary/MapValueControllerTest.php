<?php

namespace Tests\Feature\Http\Controllers\ScriptLibrary;

use App\Http\Controllers\ScriptLibrary\MapValueController;
use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class MapValueControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_guests_are_forbidden(): void
    {
        $this->postJson(route('transform-scripts.maps.values.store', 1))->assertUnauthorized();
        $this->patchJson(route('transform-scripts.maps.values.update', [1, 1]))->assertUnauthorized();
        $this->deleteJson(route('transform-scripts.maps.values.destroy', [1, 1]))->assertUnauthorized();
    }

    public function test_users_without_permissions_are_forbidden(): void
    {
        $this->passportAs($this->withoutPermissions);

        $this->postJson(route('transform-scripts.maps.values.store', 1))->assertStatus(403);
        $this->patchJson(route('transform-scripts.maps.values.update', [1, 1]))->assertStatus(403);
        $this->deleteJson(route('transform-scripts.maps.values.destroy', [1, 1]))->assertStatus(403);
    }

    public function test_users_can_store_map_values(): void
    {
        $this->partialMock(MapValueController::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(new Response(201));
        });

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('transform-scripts.maps.values.store', 1), [
                'left_value' => 'Test left map value',
                'right_value' => 'Test right map value'
            ])
            ->assertCreated();
    }

    public function test_users_can_update_map_values(): void
    {
        $this->partialMock(MapValueController::class, function ($mock) {
            $mock->shouldReceive('update')->andReturn(new Response(200));
        });

        $this
            ->passportAs($this->withPermissions)
            ->patchJson(route('transform-scripts.maps.values.update', [1, 1]), [
                'name' => 'Test Update'
            ])
            ->assertOk();
    }

    public function test_users_can_delete_map_values(): void
    {
        $this->partialMock(MapValueController::class, function ($mock) {
            $mock->shouldReceive('destroy')->andReturn(new Response(200));
        });

        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('transform-scripts.maps.values.destroy', [1, 1]))
            ->assertOk();
    }
}
