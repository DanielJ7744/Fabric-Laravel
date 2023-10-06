<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\Subscription;
use App\Models\Fabric\User;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\LaravelTestCase;

class ServiceControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->service = $this->integration->services()->save(factory(Service::class)->make());
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_users_with_permissions_can_retrieve_a_service(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.services.show', $this->service))
            ->assertOk();
    }

    public function test_users_without_permissions_cannot_retrieve_a_service(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.services.show', $this->service))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_services(): void
    {
        $attributes = factory(Service::class)->raw();
        unset($attributes['billable']);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.services.update', $this->service), $attributes)
            ->assertOk();
    }

    public function test_user_without_permission_cannot_update_services(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.services.update', $this->service), factory(Service::class)->raw())
            ->assertForbidden();
    }

    public function test_json_keys_are_not_lost_from_service_update(): void
    {
        $this->passportAs($this->withPermissions);

        $originalJsonArray = [
            'mappings' => [
                'pricelevel' => 1,
                'specialpricelevel' => 13,
                'currency' => 4
            ],
            'split_products_on_colour' => true,
            'split_products_on_material' => true,
            'page_size' => 100,
            'product_filters' => [
                [
                    'isinactive',
                    'false',
                    'is'
                ],
                [
                    'custitem_pwks_sync_item',
                    'true',
                    'is'
                ]
            ],
            'split_hierarchy_nodes' => true,
            'tag_prefixes' => [
                'custitem_ns_bsf_seasonality.name' => 'Season : ',
                'custitem_ns_bsf_key_materials' => 'Material : ',
                'custitem_ns_bsf_matcomp' => 'Material Composition : ',
                'custitem_ns_bsf_mathts' => 'Material H.T.S Code : ',
                'displayname' => 'Product Name : ',
                'custitem_ns_bsf_chain_colors.name' => 'Chain Colour : ',
                'custitem_ns_bsf_mrtx_color' => 'Parent Colours : '
            ]
        ];

        $modifiedJsonArray = [
            'product_filters' => [
                [
                    'isinactive',
                    'true',
                    'is'
                ]
            ],
        ];

        $service = factory(Service::class)->create(['from_options' => $originalJsonArray, 'username' => $this->integration->username]);

        $response = $this->putJson(route('api.v2.services.update', $service), ['from_options' => $modifiedJsonArray]);

        $response->assertOk();

        $updatedArray = $originalJsonArray;
        $updatedArray['product_filters'] = $modifiedJsonArray['product_filters'];

        $updatedService = json_decode($response->getContent(), true);
        $serviceFromOptions = $updatedService['data']['from_options'];

        $this->assertEquals($updatedArray, $serviceFromOptions);
    }

    public function test_user_cannot_enable_more_services_than_their_subscription_allows(): void
    {
        $this->service->disable();
        Subscription::unguarded(fn () => $this->company->subscriptions->first()->update(['services' => 0]));

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.services.update', $this->service), ['status' => true])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_user_with_permission_can_delete_services(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.services.destroy', $this->service))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_delete_services(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.services.destroy', $this->service))
            ->assertForbidden();
    }
}
