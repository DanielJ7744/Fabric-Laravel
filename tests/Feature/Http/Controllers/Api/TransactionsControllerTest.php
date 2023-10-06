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
class TransactionsControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->clientUser = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_users_can_retrieve_daily_transaction_counts(): void
    {
        $start = now()->subDays(rand(5, 60));
        $end = now();

        Hasura::shouldReceive('transactions')
            ->with(
                Mockery::on(fn ($usernames): bool => $usernames instanceof Collection),
                Mockery::on(fn ($date): bool => $date instanceof Carbon && $date->isSameDay($start)),
                Mockery::on(fn ($date): bool => $date instanceof Carbon && $date->isSameDay($end)),
                'daily'
            )
            ->once()
            ->andReturn(collect([
                (object) [
                    'username' => $this->integration->username
                ]
            ]));

        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.transactions.index', [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
            ]))
            ->assertOk()
            ->assertJsonPath('data.0.username', $this->integration->username);
    }

    public function test_users_can_retrieve_hourly_transaction_counts(): void
    {
        $start = now()->subHours(rand(3, 48));
        $end = now();

        Hasura::shouldReceive('transactions')
            ->with(
                Mockery::on(fn ($usernames): bool => $usernames instanceof Collection),
                Mockery::on(fn ($date): bool => $date instanceof Carbon),
                Mockery::on(fn ($date): bool => $date instanceof Carbon),
                'hourly'
            )
            ->once()
            ->andReturn(collect([
                (object) [
                    'username' => $this->integration->username
                ]
            ]));

        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.transactions.index', [
                'start' => $start->format('Y-m-d H:m:s'),
                'end' => $end->format('Y-m-d H:m:s'),
                'format' => 'hourly',
            ]))
            ->assertOk()
            ->assertJsonPath('data.0.username', $this->integration->username);
    }
}
