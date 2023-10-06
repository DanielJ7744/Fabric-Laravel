<?php

namespace Tests\Feature\Events;

use App\Events\SystemWasCreated;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\SystemType;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\LaravelTestCase;

class SystemWasCreatedTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->company = factory(Company::class)->create();
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
        $this->user = $this->company->users()->save(factory(User::class)->states('patchworks admin')->make());
        $this->system = factory(System::class)->create();
        $this->systemType = factory(SystemType::class)->create(['name' => 'Test System Type']);
    }

    public function test_admin_system_store_dispatches_event(): void
    {
        Storage::fake('images');
        Event::fake([SystemWasCreated::class]);

        $attributes = factory(System::class)->raw([
            'name' => 'Shopify',
            'system_type_id' => $this->systemType->id,
            'image' => UploadedFile::fake()->image('test-image.svg')
        ]);

        $this
            ->passportAs($this->user)
            ->postJson(route('api.v2.admin.systems.store'), $attributes)
            ->assertCreated();

        Event::assertDispatched(SystemWasCreated::class);
    }
}
