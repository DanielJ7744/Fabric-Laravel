<?php

namespace App\Providers;

use App\Models\Alerting\AlertGroups;
use App\Models\Fabric\AuthorisationType;
use App\Models\Fabric\Company;
use App\Models\Fabric\DefaultPayload;
use App\Models\Fabric\Entity;
use App\Models\Fabric\EventLog;
use App\Models\Fabric\EventType;
use App\Models\Fabric\Factory;
use App\Models\Fabric\FactorySystem;
use App\Models\Fabric\FactorySystemSchema;
use App\Models\Fabric\FactorySystemServiceOption;
use App\Models\Fabric\FilterField;
use App\Models\Fabric\FilterOperator;
use App\Models\Fabric\FilterTemplate;
use App\Models\Fabric\FilterType;
use App\Models\Fabric\InboundEndpoint;
use App\Models\Fabric\Integration;
use App\Models\Fabric\OauthClient;
use App\Models\Fabric\Mapping;
use App\Models\Fabric\ServiceOption;
use App\Models\Fabric\ServiceTemplate;
use App\Models\Fabric\ServiceTemplateOption;
use App\Models\Fabric\Subscription;
use App\Models\Fabric\System;
use App\Models\Fabric\SystemAuthorisationType;
use App\Models\Fabric\SystemType;
use App\Models\Fabric\User;
use App\Models\Fabric\Webhook;
use App\Models\Tapestry\Connector;
use App\Models\Tapestry\PaymentMap;
use App\Models\Tapestry\Service;
use App\Models\Tapestry\ServiceLog;
use App\Models\Tapestry\ShipmentMap;
use App\Policies\AlertGroupPolicy;
use App\Policies\AuthorisationTypePolicy;
use App\Policies\CompanyPolicy;
use App\Policies\ConnectorPolicy;
use App\Policies\DefaultPayloadPolicy;
use App\Policies\EntityPolicy;
use App\Policies\EventLogPolicy;
use App\Policies\EventTypePolicy;
use App\Policies\FactoryPolicy;
use App\Policies\FactorySystemPolicy;
use App\Policies\FactorySystemSchemaPolicy;
use App\Policies\FactorySystemServiceOptionPolicy;
use App\Policies\FilterFieldPolicy;
use App\Policies\FilterOperatorPolicy;
use App\Policies\FilterTemplatePolicy;
use App\Policies\FilterTypePolicy;
use App\Policies\InboundEndpointPolicy;
use App\Policies\IntegrationPolicy;
use App\Policies\OauthClientPolicy;
use App\Policies\MappingPolicy;
use App\Policies\PaymentMapPolicy;
use App\Policies\RolePolicy;
use App\Policies\ServiceLogPolicy;
use App\Policies\ServiceOptionPolicy;
use App\Policies\ServicePolicy;
use App\Policies\ServiceTemplateOptionPolicy;
use App\Policies\ServiceTemplatePolicy;
use App\Policies\ShipmentMapPolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\SystemAuthorisationTypePolicy;
use App\Policies\SystemPolicy;
use App\Policies\SystemTypePolicy;
use App\Policies\UserPolicy;
use App\Policies\WebhookPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Entity::class => EntityPolicy::class,
        System::class => SystemPolicy::class,
        Webhook::class => WebhookPolicy::class,
        Service::class => ServicePolicy::class,
        Factory::class => FactoryPolicy::class,
        Company::class => CompanyPolicy::class,
        Mapping::class => MappingPolicy::class,
        EventLog::class => EventLogPolicy::class,
        EventType::class => EventTypePolicy::class,
        Connector::class => ConnectorPolicy::class,
        FilterType::class => FilterTypePolicy::class,
        SystemType::class => SystemTypePolicy::class,
        ServiceLog::class => ServiceLogPolicy::class,
        PaymentMap::class => PaymentMapPolicy::class,
        AlertGroups::class => AlertGroupPolicy::class,
        FilterField::class => FilterFieldPolicy::class,
        OauthClient::class => OauthClientPolicy::class,
        Integration::class => IntegrationPolicy::class,
        ShipmentMap::class => ShipmentMapPolicy::class,
        Subscription::class => SubscriptionPolicy::class,
        ServiceOption::class => ServiceOptionPolicy::class,
        FactorySystem::class => FactorySystemPolicy::class,
        DefaultPayload::class => DefaultPayloadPolicy::class,
        FilterTemplate::class => FilterTemplatePolicy::class,
        FilterOperator::class => FilterOperatorPolicy::class,
        InboundEndpoint::class => InboundEndpointPolicy::class,
        ServiceTemplate::class => ServiceTemplatePolicy::class,
        AuthorisationType::class => AuthorisationTypePolicy::class,
        FactorySystemSchema::class => FactorySystemSchemaPolicy::class,
        ServiceTemplateOption::class => ServiceTemplateOptionPolicy::class,
        SystemAuthorisationType::class => SystemAuthorisationTypePolicy::class,
        FactorySystemServiceOption::class => FactorySystemServiceOptionPolicy::class,
    ];

    /**
     * The standard permission gates.
     *
     * @var array
     */
    protected $permissionGates = [
        'create companies',
        'update companies',
        'delete companies',
        'search integrations',
        'create integrations',
        'update integrations',
        'delete integrations',
        'create integration-users',
        'delete integration-users',
        'create admin-integration',
        'read admin-integration',
        'search admin-integration',
        'search permissions',
        'read permissions',
        'create permissions',
        'update permissions',
        'delete permissions',
        'read roles',
        'create roles',
        'update roles',
        'delete roles',
        'search systems',
        'read systems',
        'create systems',
        'update systems',
        'delete systems',
        'search service-logs',
        'read service-logs',
        'create service-logs',
        'update service-logs',
        'delete service-logs',
        'create alert-recipients',
        'update alert-recipients',
        'delete alert-recipients',
        'read alert-recipients',
        'search alert-recipients',
        'create alert-groups',
        'update alert-groups',
        'delete alert-groups',
        'read alert-groups',
        'search alert-groups',
        'create alert-configs',
        'update alert-configs',
        'delete alert-configs',
        'read alert-configs',
        'search alert-configs',
        'read alert-manager',
        'search alert-manager',
        'update alert-manager',
        'search entities',
        'read entities',
        'create entities',
        'update entities',
        'delete entities',
        'search filter-templates',
        'read filter-templates',
        'create filter-templates',
        'update filter-templates',
        'delete filter-templates',
        'search report-sync-counts',
        'search report-sync-results',
        'search report-sync-filter-options',
        'search services',
        'create services',
        'read services',
        'update services',
        'search system-types',
        'read system-types',
        'create system-types',
        'update system-types',
        'delete system-types',
        'read mappings',
        'create mappings',
        'delete mappings',
        'search filter-fields',
        'create filter-fields',
        'read filter-fields',
        'update filter-fields',
        'delete filter-fields',
        'search filter-types',
        'create filter-types',
        'read filter-types',
        'update filter-types',
        'delete filter-types',
        'search filter-operators',
        'create filter-operators',
        'read filter-operators',
        'update filter-operators',
        'delete filter-operators',
        'search system-authorisation-types',
        'create system-authorisation-types',
        'read system-authorisation-types',
        'update system-authorisation-types',
        'delete system-authorisation-types',
        'search authorisation-types',
        'create authorisation-types',
        'read authorisation-types',
        'update authorisation-types',
        'delete authorisation-types',
        'search maps',
        'create maps',
        'read maps',
        'update maps',
        'delete maps',
        'create map-values',
        'update map-values',
        'delete map-values',
        'search connectors',
        'create connectors',
        'read connectors',
        'update connectors',
        'delete connectors',
        'search inbound-apis',
        'read inbound-apis',
        'update inbound-apis',
        'search inbound-credentials',
        'create inbound-credentials',
        'read inbound-credentials',
        'update inbound-credentials',
        'delete inbound-credentials',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(now()->addDay());
        Passport::refreshTokensExpireIn(now()->addHours(12));
        Passport::personalAccessTokensExpireIn(now()->addHours(12));
        Passport::useClientModel(OauthClient::class);
        Passport::tokensCan([
            'access-api' => 'Access your company and personal information via the Patchworks API.',
            'access-inbound' => 'Post data directly to a Patchworks service via a dedicated API endpoint.',
        ]);

        Gate::before(function ($user, $ability) {
            if (in_array($ability, $this->permissionGates)) {
                return $user->hasPermissionTo($ability);
            }
        });

        Gate::define('search companies', function (User $user) {
            return $user->hasAnyPermission(['search companies', 'search owned companies']);
        });

        Gate::define('read companies', function (User $user, Company $company) {
            if ($user->hasPermissionTo('read owned companies')) {
                return $user->company && $user->company->is($company);
            }

            return $user->hasPermissionTo('read companies');
        });

        Gate::define('assign companies', function (User $user, Request $request) {
            if (!$user->hasPermissionTo('assign companies')) {
                return false;
            }

            $content = json_decode($request->getContent(), true);
            $clientAdminRole = Role::firstWhere('name', 'client admin');

            if ($content['data'] === null && $user->hasRole($clientAdminRole)) {
                $user->removeRole($clientAdminRole);
            } elseif ($content['data'] !== null && !$user->hasRole($clientAdminRole)) {
                $user->assignRole($clientAdminRole);
            }

            return true;
        });

        Gate::define('read integrations', function (User $user, Integration $integration) {
            if ($user->hasPermissionTo('read owned integrations')) {
                return $user->company_id
                    ? $user->company->integrations->contains($integration)
                    : false;
            }

            return $user->hasPermissionTo('read integrations');
        });

        Gate::define('search roles', function (User $user) {
            return $user->hasAnyPermission(['search roles', 'search client roles']);
        });
    }
}
