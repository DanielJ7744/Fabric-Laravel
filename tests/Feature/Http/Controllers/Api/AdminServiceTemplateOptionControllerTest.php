<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\Company;
use App\Models\Fabric\Entity;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\ServiceOption;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\ServiceTemplateOption;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AdminServiceTemplateOptionControllerTest extends LaravelTestCase
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
        $this->serviceOption = factory(ServiceOption::class)->create(['key' => 'Random Key']);
        $this->serviceTemplateOption = factory(ServiceTemplateOption::class)->create([
            'value' => 'Test Value',
            'target' => 'destination',
            'service_option_id' => $this->serviceOption->id,
            'service_template_id' => $this->serviceTemplate->id
        ]);
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
    }

    public function test_user_with_permission_can_retrieve_service_template_options(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.admin.service-templates.options.index', $this->serviceTemplate))
            ->assertOk();
    }

    public function test_user_without_permission_cannot_retrieve_service_template_options(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.admin.service-templates.options.index', $this->serviceTemplate))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_retrieve_a_service_template_option(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.admin.service-templates.options.show', [$this->serviceTemplate, $this->serviceTemplateOption]))
            ->assertOk()
            ->assertJsonPath('data.id', $this->serviceTemplateOption->id);
    }

    public function test_user_without_permission_cannot_retrieve_a_service_template_option(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson(route('api.v2.admin.service-templates.options.show', [$this->serviceTemplate, $this->serviceTemplateOption]))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_create_a_service_template(): void
    {
        $attributes = factory(ServiceTemplateOption::class)->raw([
            'value' => 'Test Option',
            'target' => 'source',
            'service_option_id' => $this->serviceOption->id,
            'service_template_id' => $this->serviceTemplate->id
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->postJson(route('api.v2.admin.service-templates.options.store', $this->serviceTemplate), $attributes)
            ->assertCreated();

        $attributes['value'] = json_encode($attributes['value']);

        $this->assertDatabaseHas((new ServiceTemplateOption())->getTable(), $attributes);
    }

    public function test_user_without_permission_cannot_create_a_service_template_option(): void
    {
        $attributes = factory(ServiceTemplateOption::class)->raw([
            'value' => 'Test Option',
            'target' => 'source',
            'service_option_id' => $this->serviceOption->id,
            'service_template_id' => $this->serviceTemplate->id
        ]);

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson(route('api.v2.admin.service-templates.options.store', $this->serviceTemplate), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_update_a_service_template(): void
    {
        $attributes = factory(ServiceTemplateOption::class)->raw([
            'value' => 'Updated Option',
        ]);

        $this
            ->passportAs($this->withPermissions)
            ->putJson(route('api.v2.admin.service-templates.options.update', [$this->serviceTemplate, $this->serviceTemplateOption]), $attributes)
            ->assertOk();

        $this->assertSame($attributes['value'], $this->serviceTemplateOption->fresh()->value);
    }

    public function test_user_without_permission_cannot_update_a_service_template_option(): void
    {
        $attributes = [
            'value' => 'Updated value'
        ];

        $this
            ->passportAs($this->withoutPermissions)
            ->putJson(route('api.v2.admin.service-templates.options.update', [$this->serviceTemplate, $this->serviceTemplateOption]), $attributes)
            ->assertForbidden();
    }

    public function test_user_with_permission_can_delete_a_service_template_option(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->deleteJson(route('api.v2.admin.service-templates.options.destroy', [$this->serviceTemplate, $this->serviceTemplateOption]))
            ->assertOk()
            ->assertJsonPath('message', 'Service template option deleted successfully.');

        $this->assertDatabaseMissing($this->serviceTemplateOption->getTable(), $this->serviceTemplateOption->only('id'));
    }

    public function test_user_without_permission_cannot_delete_a_service_template_option(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->deleteJson(route('api.v2.admin.service-templates.options.destroy', [$this->serviceTemplate, $this->serviceTemplateOption]))
            ->assertForbidden();
    }
}
