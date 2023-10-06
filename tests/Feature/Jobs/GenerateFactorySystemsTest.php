<?php

namespace Tests\Feature\Jobs;

use App\Jobs\GenerateFactorySystems;
use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class GenerateFactorySystemsTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());

        $this->system = factory(System::class)->create(['factory_name' => 'Shopify']);
        $this->factory = factory(Factory::class)->create(['name' => 'Orders']);
        $this->entity = factory(Entity::class)->create(['name' => 'Orders']);

        $this->service = $this->integration->services()->save(factory(Service::class)->make(['from_factory' => 'Shopify\\Pull\\Orders']));

        $this->generatedFactorySystem = factory(FactorySystem::class)->raw([
            'system_id' => $this->system->id,
            'factory_id' => $this->factory->id,
            'entity_id' => $this->entity->id,
            'direction' => 'pull',
            'default_map_name' => null,
            'display_name' => $this->entity->name
        ]);
    }

    public function test_factory_system_is_created(): void
    {
        GenerateFactorySystems::dispatchNow($this->system);

        $this->assertDatabaseHas((new FactorySystem())->getTable(), $this->generatedFactorySystem);
    }
}
