<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\EventLog;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class EventLogControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->eventLog = $this->company->eventLogs()->save(factory(EventLog::class)->make());
        $this->patchworks = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->customer = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->user = factory(User::class)->create();
    }

    public function test_patchworks_can_retrieve_company_event_logs(): void
    {
        $this
            ->passportAs($this->patchworks)
            ->getJson(route('api.v2.event-logs.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->eventLog->id);
    }

    public function test_customers_can_retrieve_company_event_logs(): void
    {
        $this
            ->passportAs($this->customer)
            ->getJson(route('api.v2.event-logs.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->eventLog->id);
    }

    public function test_users_without_a_company_cannot_retrieve_company_event_logs(): void
    {
        $this
            ->passportAs($this->user)
            ->getJson(route('api.v2.event-logs.index'))
            ->assertForbidden();
    }
}
