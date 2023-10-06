<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Alerting\AlertGroups;
use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AlertGroupControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->alertGroup = $this->company->alertGroups()->save(factory(AlertGroups::class)->make());
        $this->clientUser = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_can_retrieve_alert_groups(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.alert-groups.index'))
            ->assertOk()
            ->assertJsonPath('data.0.id', $this->alertGroup->id);
    }

    public function test_can_retrieve_an_alert_group(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->getJson(route('api.v2.alert-groups.show', $this->alertGroup))
            ->assertOk()
            ->assertJsonPath('data.id', $this->alertGroup->id);
    }

    public function test_can_create_an_alert_group_with_permission(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->postJson(
                route('api.v2.alert-groups.store'),
                factory(AlertGroups::class)->raw()
            )
            ->assertCreated();
    }

    public function test_can_update_an_alert_group_with_permission(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->putJson(
                route('api.v2.alert-groups.update', $this->alertGroup),
                factory(AlertGroups::class)->raw()
            )
            ->assertOk();
    }

    public function test_can_delete_an_alert_group_with_permission(): void
    {
        $this
            ->passportAs($this->clientUser)
            ->deleteJson(route('api.v2.alert-groups.destroy', $this->alertGroup))
            ->assertOk();
    }
}
