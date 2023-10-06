<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Helpers\ReportSyncHelper;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use App\Models\Tapestry\Connector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class SyncReportsControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $integration = $this->company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->record = tap(factory(Connector::class)->make()->setIdxTable($integration->username))->save();
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_users_with_permissions_can_retrieve_syncs(): void
    {
        $this
            ->mock(ReportSyncHelper::class)
            ->shouldReceive('getEntityResyncData')
            ->andReturn([
                'resync_column' => 'test',
                'filter_values' => [1234],
                'filter_template_id' => 1
            ]);

        $this
            ->passportAs($this->user)
            ->getJson(route('api.v2.sync-reports.index'))
            ->assertOk();
    }

    public function test_can_retrieve_records_with_permission(): void
    {
        $this
            ->mock(ReportSyncHelper::class)
            ->shouldReceive('getEntityResyncData')
            ->andReturn([
                'resync_column' => 'test',
                'filter_values' => [1234],
                'filter_template_id' => 1
            ]);

        $this
            ->passportAs($this->user)
            ->getJson(route('api.v2.sync-reports.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->record->getOriginal('id'));
    }
}
