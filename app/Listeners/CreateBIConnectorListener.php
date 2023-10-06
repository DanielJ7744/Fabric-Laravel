<?php

namespace App\Listeners;

use App\Enums\Systems;
use App\Events\CompanySubscriptionUpdated;
use App\Events\IntegrationCreated;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Tapestry\Connector;

class CreateBIConnectorListener
{
    public function handle($event): void
    {
        switch (get_class($event)) {
            case IntegrationCreated::class:
                $this->integrationCreated($event);
                break;
            case CompanySubscriptionUpdated::class:
                $this->companySubscriptionUpdated($event);
                break;
        }
    }

    public function integrationCreated(IntegrationCreated $event): void
    {
        if (!$event->integration->company->subscriptionAllowance()->business_insights) {
            return;
        }

        $this->createBIConnector($event->integration);
    }

    public function companySubscriptionUpdated(CompanySubscriptionUpdated $event): void
    {
        if (!$event->subscription->business_insights) {
            return;
        }

        $event->company->integrations->each(fn ($integration) => $this->createBIConnector($integration));
    }

    public function createBIConnector(Integration $integration): void
    {
        $system = System::firstWhere(['factory_name' => Systems::BI]);
        $connector = Connector::make([
            'type' =>  Connector::TYPE,
            'common_ref' => 'live',
            'system_chain' => $system->factory_name,
            'extra' => [
                'connector_name' => 'BI',
                'timezone' => 'UTC',
                'date_format' => 'YYYY-MM-DD'
            ]
        ]);
        $connector->setIdxTable($integration->username)->save();
    }
}
