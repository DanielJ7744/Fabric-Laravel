<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Helpers\IntegrationHelper;
use App\Http\Helpers\ServiceHelper;
use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\Integration;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\Subscription;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class IntegrationServiceControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $baseTierCompany = factory(Company::class)->create();
        $baseSubscription = Subscription::find('name', 'Base');
        $baseTierCompany->subscriptions()->syncWithoutDetaching($baseSubscription);
        $this->baseTierUser = $baseTierCompany->users()->save(factory(User::class)->make());
        $this->entity = factory(Entity::class)->create();
        $this->sourceSystem = factory(System::class)->create(['factory_name' => 'test1']);
        $this->destinationSystem = factory(System::class)->create(['factory_name' => 'test2']);
        $this->sourceFactory = factory(Factory::class)->create();
        $this->destinationFactory = factory(Factory::class)->create();
        $this->sourceFactorySystem = factory(FactorySystem::class)->create([
            'system_id' => $this->sourceSystem->id,
            'entity_id' => $this->entity->id,
            'factory_id' => $this->sourceFactory->id,
            'direction' => 'pull'
        ]);
        $this->destinationFactorySystem = factory(FactorySystem::class)->create([
            'system_id' => $this->destinationSystem->id,
            'entity_id' => $this->entity->id,
            'factory_id' => $this->destinationFactory->id,
            'direction' => 'push'
        ]);
        $this->serviceTemplate = factory(ServiceTemplate::class)->create([
            'source_factory_system_id' => $this->sourceFactorySystem->id,
            'destination_factory_system_id' => $this->destinationFactorySystem->id
        ]);

        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->baseTierIntegration = $baseTierCompany->integrations()->save(factory(Integration::class)->make());
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_users_with_permissions_can_retrieve_services(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.integrations.services.index', $this->integration))
            ->assertOk();
    }

    public function test_users_without_permissions_cannot_retrieve_services(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.integrations.services.index', $this->integration))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_create_services(): void
    {
        $this->expectsEvents('eloquent.created: ' . Service::class);

        $fromFactory = ServiceHelper::getBaseFactoryString(
            $this->sourceSystem->factory_name,
            'Pull',
            $this->sourceFactory->name
        );
        $toFactory = ServiceHelper::getBaseFactoryString(
            $this->destinationSystem->factory_name,
            'Push',
            $this->destinationFactory->name
        );
        $service = factory(Service::class)->create([
            'username' => $this->integration->username,
            'from_factory' => $fromFactory,
            'to_factory' => $toFactory
        ]);

        $attributes = factory(Service::class)->raw([
            'entity_id' => $this->entity->id,
            'source_system_id' => $this->sourceSystem->id,
            'destination_system_id' => $this->destinationSystem->id,
            'service_template_id' => $this->serviceTemplate->id,
            'from_factory' => $fromFactory,
            'to_factory' => $toFactory
        ]);

        unset($attributes['billable']);

        $this->partialMock(IntegrationHelper::class, function ($mock) use ($service) {
            $mock->shouldReceive('createService')->once()->andReturn($service->toArray());
        });

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.integrations.services.store', $this->integration), $attributes)
            ->assertCreated();
    }

    public function test_creating_netsuite_service_creates_confirmation_service(): void
    {
        $shopifySystem = factory(System::class)->create([
            'name' => 'Shopify',
            'factory_name' => 'Shopify'
        ]);
        $netsuiteSystem = factory(System::class)->create([
            'name' => 'Netsuite',
            'factory_name' => 'Netsuite'
        ]);
        $orderFactory = Factory::firstWhere('name', 'Orders') ?: factory(Factory::class)->create([
            'name' => 'Orders'
        ]);
        $orderEntity = Entity::firstWhere('name', 'Orders') ?: factory(Entity::class)->create([
            'name' => 'Orders'
        ]);
        $sourceFactorySystem = factory(FactorySystem::class)->create([
            'system_id' => $shopifySystem->id,
            'entity_id' => $orderEntity->id,
            'factory_id' => $orderFactory->id,
            'direction' => 'pull'
        ]);
        $destinationFactorySystem = factory(FactorySystem::class)->create([
            'system_id' => $netsuiteSystem->id,
            'entity_id' => $orderEntity->id,
            'factory_id' => $orderFactory->id,
            'direction' => 'push'
        ]);
        $serviceTemplate = factory(ServiceTemplate::class)->create([
            'source_factory_system_id' => $sourceFactorySystem->id,
            'destination_factory_system_id' => $destinationFactorySystem->id
        ]);
        $service = factory(Service::class)->create([
            'username' => $this->integration->username,
        ]);
        $attributes = factory(Service::class)->raw([
            'service_template_id' => $serviceTemplate->id,
        ]);
        $attributes['to_options']['origin'] = 'Test';

        $this->partialMock(IntegrationHelper::class, function ($mock) use ($service) {
            $mock->shouldReceive('createService')->andReturn($service->toArray());
        });

        unset($attributes['billable']);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.integrations.services.store', $this->integration), $attributes)
            ->assertCreated();
    }

    public function test_user_without_permission_cannot_create_services(): void
    {
        $attributes = factory(Service::class)->raw([
            'entity_id' => $this->entity->id,
            'source_system_id' => $this->sourceSystem->id,
            'destination_system_id' => $this->destinationSystem->id,
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.integrations.services.store', $this->integration), $attributes)
            ->assertForbidden();
    }

    public function test_base_tier_user_cannot_create_services(): void
    {
        $attributes = factory(Service::class)->raw([
            'entity_id' => $this->entity->id,
            'source_system_id' => $this->sourceSystem->id,
            'destination_system_id' => $this->destinationSystem->id,
            'username' => $this->baseTierIntegration->username
        ]);

        $this
            ->passportAs($this->baseTierUser)
            ->postJson(route('api.v2.integrations.services.store', $this->baseTierIntegration), $attributes)
            ->assertForbidden();
    }
}
