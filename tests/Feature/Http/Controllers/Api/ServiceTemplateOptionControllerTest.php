<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\ServiceTemplateOption;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class ServiceTemplateOptionControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->factory = factory(Factory::class)->create();
        $this->shopify = factory(System::class)->create(['name' => 'Shopify']);
        $this->peoplevox = factory(System::class)->create(['name' => 'Peoplevox']);
        $this->entity = factory(Entity::class)->create();
        $this->shopifyFactorySystem = factory(FactorySystem::class)->create([
            'factory_id' => $this->factory->id,
            'system_id' => $this->shopify->id,
            'entity_id' => $this->entity->id
        ]);
        $this->peoplevoxFactorySystem = factory(FactorySystem::class)->create([
            'factory_id' => $this->factory->id,
            'system_id' => $this->peoplevox->id,
            'entity_id' => $this->entity->id
        ]);
        $this->serviceTemplate = factory(ServiceTemplate::class)->create();
        $this->serviceTemplateOption = $this->serviceTemplate->serviceTemplateOptions()->save(factory(ServiceTemplateOption::class)->make());
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_user_with_permission_can_retrieve_service_template_options(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.service-templates.options.index', $this->serviceTemplate))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_service_template_options(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.service-templates.options.index', $this->serviceTemplate))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_service_template_option(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.service-templates.options.show', [$this->serviceTemplate, $this->serviceTemplateOption]))
            ->assertOk()
            ->assertJsonPath('data.id', $this->serviceTemplateOption->id);
    }

    public function test_user_without_permission_cannot_retrieve_a_service_template_option(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.service-templates.options.show', [$this->serviceTemplate, $this->serviceTemplateOption]))
            ->assertForbidden();
    }
}
