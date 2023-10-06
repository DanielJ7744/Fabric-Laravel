<?php

namespace Tests\Feature\Models\Fabric;

use App\Models\Fabric\Company;
use App\Models\Fabric\EventType;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\Webhook;
use App\Models\Tapestry\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class WebhookTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->peoplevoxSystem = factory(System::class)->create(['factory_name' => 'Peoplevox']);
        $this->shopifySystem = factory(System::class)->create(['factory_name' => 'Shopify']);
        $company = factory(Company::class)->create();
        $this->integration = $company->integrations()->save(factory(Integration::class)->make(['username' => 'table']));
        $this->service = $this->integration->services()->save(factory(Service::class)->make([
            'username' => $this->integration->username,
            'from_factory' => 'Peoplevox\\Pull\\Orders',
            'to_factory' => 'Shopify\\Push\\Orders',
        ]));
        $this->eventType = factory(EventType::class)->create([
            'system_id' => $this->peoplevoxSystem->id,
            'key' => 'OrderDeleted'
        ]);
        $this->webhook = factory(Webhook::class)->create([
            'integration_id' => $this->integration->id,
            'service_id' => $this->service->id,
            'event_type_id' => $this->eventType->id
        ]);
    }

    public function test_callback_url(): void
    {
        $this->assertEquals(
            sprintf(
                '%s/%s/post/%s/%s/%s/%s/%s',
                config('webhook.host'),
                'Peoplevox',
                'webhook',
                'table',
                'OrderDeleted',
                'Shopify',
                $this->service->id
            ),
            $this->webhook->getCallbackUrl()
        );
    }
}
