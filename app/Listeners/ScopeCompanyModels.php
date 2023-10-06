<?php

namespace App\Listeners;

use App\Models\Alerting\AlertGroups;
use App\Models\Fabric\Entity;
use App\Models\Fabric\EventLog;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemSchema;
use App\Models\Fabric\InboundEndpoint;
use App\Models\Fabric\Integration;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\User;
use App\Models\Fabric\Webhook;
use App\Models\Scopes\BelongsToCompany;
use App\Models\Tapestry\Service;
use App\Models\Tapestry\ServiceLog;
use Illuminate\Database\Eloquent\Builder;

class ScopeCompanyModels
{
    /**
     * The models that belong to the current company.
     *
     * @var array
     */
    protected array $belongsToCompany = [
        User::class,
        EventLog::class,
        AlertGroups::class,
        Integration::class,
    ];

    /**
     * The models that belong to the current company's integrations.
     *
     * @var array
     */
    protected array $belongsToIntegration = [
        Webhook::class,
        InboundEndpoint::class,
    ];

    /**
     * The models that belong to the current company's integrations by username.
     *
     * @var array
     */
    protected array $belongsToIntegrationByUsername = [
        Service::class,
        ServiceLog::class
    ];

    /**
     * The models that belong to the current company's integrations or are globally available when integration_id is null
     *
     * @var array
     */
    protected $belongsToIntegrationOrGlobal = [
        FactorySystem::class,
        FactorySystemSchema::class,
        Entity::class,
        ServiceTemplate::class,
    ];

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        foreach ($this->belongsToCompany as $model) {
            $model::addGlobalScope(new BelongsToCompany);
        }

        $integrationIds = $event->tenant->getIntegrationIds();

        foreach ($this->belongsToIntegration as $model) {
            $model::addGlobalScope('BelongsToIntegration', fn (Builder $builder) => $builder->whereIn('integration_id', $integrationIds));
        }

        foreach ($this->belongsToIntegrationOrGlobal as $model) {
            $model::addGlobalScope('BelongsToIntegrationOrGlobal', fn (Builder $builder) => $builder->whereIn('integration_id', $integrationIds)->orWhereNull('integration_id'));
        }

        // We are unable to use whereHas() here because it isn't supported with our Laravel version/setup
        // so until we can use a foreign key or update then we can cache our scoped integration usernames and
        // use these to filter the services. Integration usernames are cached and only fetched once per request.
        $integrationUsernames = $event->tenant->getIntegrationUsernames();

        foreach ($this->belongsToIntegrationByUsername as $model) {
            $model::addGlobalScope('BelongsToIntegrationByUsername', fn (Builder $builder) => $builder->whereIn('username', $integrationUsernames));
        }
    }
}
