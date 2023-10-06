<?php

namespace Tests\Feature\Http\Controllers\ScriptLibrary;

use App\Http\Controllers\ScriptLibrary\BatchController;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class BatchControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    private string $mapUri = 'api/v1/transform-scripts/maps';

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function test_unauthenticated_users_are_forbidden(): void
    {
        $this
            ->postJson(route('transform-scripts.batch.store'))
            ->assertUnauthorized();
    }

    public function test_batch_request_validation(): void
    {
        $this
            ->passportAs($this->user)
            ->postJson(route('transform-scripts.batch.store'), ['batch' => [[]]])
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'batch.0.method' => ['The batch.0.method field is required when batch is present.'],
                    'batch.0.relative_uri' => ['The batch.0.relative_uri field is required when batch is present.']
                ],
            ])
            ->assertStatus(422);
    }

    public function test_batch_request_validation_body_field(): void
    {
        $this
            ->passportAs($this->user)
            ->postJson(route('transform-scripts.batch.store'), ['batch' => [[
                'method' => 'e',
                'relative_uri' => 't',
                'body' => ''
            ]]])
            ->assertJsonValidationErrors([
                'batch.0.body' => 'The batch.0.body field must have a value.'
            ])
            ->assertStatus(422);
    }

    public function test_batch_route_must_be_valid(): void
    {
        $this
            ->passportAs($this->user)
            ->postJson(route('transform-scripts.batch.store'), ['batch' => [[
                'method' => 'GET',
                'relative_uri' => 'test/invalid/route',
            ]]])
            ->assertJson([[
                'code' => 404,
                'message' => '',
                'original' => [
                    'method' => 'GET',
                    'relative_uri' => 'test/invalid/route'
                ],
            ]])
            ->assertStatus(207);
    }

    public function test_batch_request_body_is_not_returned(): void
    {
        $this
            ->passportAs($this->user)
            ->postJson(route('transform-scripts.batch.store'), ['batch' => [[
                'method' => 'GET',
                'relative_uri' => 'test/invalid/route',
                'body' => [
                    'test'
                ]
            ]]])
            ->assertJson([[
                'code' => 404,
                'message' => '',
                'original' => [
                    'method' => 'GET',
                    'relative_uri' => 'test/invalid/route'
                ],
            ]])
            ->assertStatus(207);
    }

    public function test_batch_route_must_support_method(): void
    {
        $this
            ->passportAs($this->user)
            ->postJson(route('transform-scripts.batch.store'), ['batch' => [[
                'method' => 'TRACE',
                'relative_uri' => $this->mapUri,
            ]]])
            ->assertJson([[
                'code' => 405,
                'message' => 'The TRACE method is not supported for this route. Supported methods: GET, HEAD, POST.',
                'original' => [
                    'method' => 'TRACE',
                    'relative_uri' => 'api/v1/transform-scripts/maps'
                ],
            ]])
            ->assertStatus(207);
    }

    public function test_batch_request_must_be_authorized(): void
    {
        $this
            ->passportAs($this->user)
            ->postJson(route('transform-scripts.batch.store'), ['batch' => [
                [
                    'method' => 'GET',
                    'relative_uri' => $this->mapUri,
                ],
                [
                    'method' => 'POST',
                    'relative_uri' => $this->mapUri,
                ]
            ]])
            ->assertJson([
                [
                    'code' => 403,
                    'message' => 'Forbidden.',
                    'original' => [
                        'method' => 'GET',
                        'relative_uri' => 'api/v1/transform-scripts/maps'
                    ],
                ],
                [
                    'code' => 403,
                    'message' => 'Forbidden.',
                    'original' => [
                        'method' => 'POST',
                        'relative_uri' => 'api/v1/transform-scripts/maps'
                    ],
                ]
            ])
            ->assertStatus(207);
    }

    public function test_batch_request_controller_supports_batching(): void
    {
        $this->user->givePermissionTo('search maps');

        $this->partialMock(BatchController::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('controllerSupportsBatching')->andReturn(false);
        });

        $this
            ->passportAs($this->user)
            ->postJson(route('transform-scripts.batch.store'), ['batch' => [[
                'method' => 'GET',
                'relative_uri' => $this->mapUri,
            ]]])
            ->assertJson([[
                'code' => 405,
                'message' => 'This endpoint does not support request batching.',
                'original' => [
                    'method' => 'GET',
                    'relative_uri' => 'api/v1/transform-scripts/maps'
                ],
            ]])
            ->assertStatus(207);
    }
}
