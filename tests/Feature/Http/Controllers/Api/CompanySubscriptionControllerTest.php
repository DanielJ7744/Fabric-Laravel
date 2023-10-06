<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Facades\Hasura;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Mockery;
use Tests\LaravelTestCase;

/**
 * @group hasura
 */
class CompanySubscriptionControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->subscription = $this->company->subscriptions()->first();
        $this->user = $this->company->users()->save(factory(User::class)->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
    }

    public function test_users_can_retrieve_their_companies_subscriptions(): void
    {
        $startOfMonth = now()->startOfMonth();

        Hasura::shouldReceive('transactions')
            ->with(
                Mockery::on(fn ($usernames): bool => $usernames instanceof Collection),
                Mockery::on(fn ($date): bool => $date instanceof Carbon && $date->isSameDay($startOfMonth)),
                Mockery::on(fn ($date): bool => $date instanceof Carbon && $date->isSameDay(now())),
            )
            ->once()
            ->andReturn(collect([
                (object) [
                    'username' => $this->integration->username,
                    'total_transactions' => 30
                ],
                (object) [
                    'username' => $this->integration->username,
                    'total_transactions' => 15
                ]
            ]));

        $this
            ->passportAs($this->user)
            ->getJson(route('api.v2.company-subscriptions.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->subscription->getKey())
            ->assertJsonPath('data.0.name', $this->subscription->name)
            ->assertJsonPath('summary.usage.transaction_count', 45)
            ->assertJsonStructure([
                'summary' => [
                    'usage' => [
                        'active_services',
                        'active_users',
                        'transaction_count'
                    ]
                ]
            ]);
    }
}
