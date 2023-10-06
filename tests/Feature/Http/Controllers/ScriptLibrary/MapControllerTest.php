<?php

namespace Tests\Feature\Http\Controllers\ScriptLibrary;

use App\Http\Controllers\ScriptLibrary\MapController;
use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class MapControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    private string $uri = 'api/v1/transform-scripts/maps';

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_guests_are_forbidden(): void
    {
        $this->getJson(route('transform-scripts.maps.index'))->assertUnauthorized();
        $this->postJson(route('transform-scripts.maps.store'))->assertUnauthorized();
        $this->getJson(route('transform-scripts.maps.show', 1))->assertUnauthorized();
        $this->patchJson(route('transform-scripts.maps.update', 1))->assertUnauthorized();
        $this->deleteJson(route('transform-scripts.maps.destroy', 1))->assertUnauthorized();
    }

    public function test_users_without_permissions_are_forbidden(): void
    {
        $this->passportAs($this->withoutPermissions);

        $this->getJson(route('transform-scripts.maps.index'))->assertStatus(403);
        $this->postJson(route('transform-scripts.maps.store'))->assertStatus(403);
        $this->getJson(route('transform-scripts.maps.show', 1))->assertStatus(403);
        $this->patchJson(route('transform-scripts.maps.update', 1))->assertStatus(403);
        $this->deleteJson(route('transform-scripts.maps.destroy', 1))->assertStatus(403);
    }

    public function test_users_can_retrieve_maps(): void
    {
        $this->partialMock(MapController::class, function ($mock) {
            $mock->shouldReceive('index')->andReturn(new Response(200));
        });

        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('transform-scripts.maps.index'))
            ->assertOk();
    }


    public function test_users_can_create_maps(): void
    {
        $this->partialMock(MapController::class, function ($mock) {
            $mock->shouldReceive('store')->andReturn(new Response(201));
        });

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('transform-scripts.maps.store'), [
                'name' => 'Test Create',
                'description' => 'Valid create map test'
            ])
            ->assertCreated();
    }

    public function test_users_can_retrieve_a_map(): void
    {
        $this->partialMock(MapController::class, function ($mock) {
            $mock->shouldReceive('show')->andReturn(new Response(200));
        });

        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('transform-scripts.maps.show', 1))
            ->assertOk();
    }

    public function test_users_can_update_maps(): void
    {
        $this->partialMock(MapController::class, function ($mock) {
            $mock->shouldReceive('update')->andReturn(new Response(200));
        });

        $this->passportAs($this->withPermissions)
            ->patchJson(route('transform-scripts.maps.update', 1), [
                'name' => 'Test Update'
            ])
            ->assertOk();
    }

    public function test_users_can_delete_maps(): void
    {
        $this->partialMock(MapController::class, function ($mock) {
            $mock->shouldReceive('destroy')->andReturn(new Response(200));
        });

        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('transform-scripts.maps.destroy', 1))
            ->assertOk();
    }
}
