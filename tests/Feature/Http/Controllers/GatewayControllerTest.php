<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class GatewayControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function test_it_forwards_get_requests(): void
    {
        $this->markTestSkipped(
            'This test has not been implemented yet.'
        );

        $this
            ->passportAs($this->user)
            ->getJson(route('api.v1.transform-scripts.forward.get', ['path' => 'complete-me']))
            ->assertOk();
    }

    public function test_it_forwards_post_requests(): void
    {
        $this->markTestSkipped(
            'This test has not been implemented yet.'
        );

        $this
            ->passportAs($this->user)
            ->getJson(route('api.v1.transform-scripts.forward.post', ['path' => 'complete-me']))
            ->assertOk();
    }
}
