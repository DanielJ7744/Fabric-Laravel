<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Helpers\PaymentMapHelper;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use App\Models\Tapestry\PaymentMap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class IntegrationPaymentMapControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->withPermission = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->withoutPermission = $this->company->users()->save(factory(User::class)->make());
    }

    public function test_user_with_permission_can_retrieve_payment_map(): void
    {
        $this
            ->mock(PaymentMapHelper::class)
            ->shouldReceive('get')
            ->andReturn([
                'data' => [
                    'fallback' => 'test',
                    'methods' => [
                        'test' => [
                            'input_key' => 'payment_gateway',
                            'match' => 'contains',
                            'output' => 'test test'
                        ]
                    ]
                ],
            ]);

        $this
            ->passportAs($this->withPermission)
            ->getJson(route('api.v2.integrations.payment-maps.index', $this->integration))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_payment_map(): void
    {
        $this
            ->mock(PaymentMapHelper::class)
            ->shouldReceive('get')
            ->andReturn([
                'data' => [
                    'fallback' => 'test',
                    'methods' => [
                        'test' => [
                            'input_key' => 'payment_gateway',
                            'match' => 'contains',
                            'output' => 'test test'
                        ]
                    ]
                ],
            ]);

        $this
            ->passportAs($this->withoutPermission)
            ->getJson(route('api.v2.integrations.payment-maps.index', $this->integration))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_payment_map(): void
    {
        $this
            ->mock(PaymentMapHelper::class)
            ->shouldReceive('post')
            ->andReturn([
                'data' => [
                    'fallback' => 'test',
                    'methods' => [
                        'test' => [
                            'input_key' => 'payment_gateway',
                            'match' => 'contains',
                            'output' => 'test test'
                        ]
                    ]
                ],
            ]);

        $attributes = factory(PaymentMap::class)->raw();

        $this
            ->passportAs($this->withPermission)
            ->postJson(route('api.v2.integrations.payment-maps.store', $this->integration), $attributes)
            ->assertOk();
    }

    public function test_user_without_permission_cannot_update_payment_map(): void
    {
        $this
            ->mock(PaymentMapHelper::class)
            ->shouldReceive('post')
            ->andReturn([
                'data' => [
                    'fallback' => 'test',
                    'methods' => [
                        'test' => [
                            'input_key' => 'payment_gateway',
                            'match' => 'contains',
                            'output' => 'test test'
                        ]
                    ]
                ],
            ]);

        $attributes = factory(PaymentMap::class)->raw();

        $this
            ->passportAs($this->withoutPermission)
            ->postJson(route('api.v2.integrations.payment-maps.store', $this->integration), $attributes)
            ->assertForbidden();
    }
}
