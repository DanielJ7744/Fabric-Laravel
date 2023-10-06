<?php

namespace App\Providers;

use App\Events\CompanySubscriptionUpdated;
use App\Events\IntegrationCreated;
use App\Events\PasswordResetFailed;
use App\Events\PasswordResetRequested;
use App\Events\PasswordUpdated;
use App\Events\PasswordUpdateFailed;
use App\Events\ServiceScheduled;
use App\Events\ServiceScheduleFailed;
use App\Events\SystemWasCreated;
use App\Listeners\AuditedListener;
use App\Listeners\CreateBIConnectorListener;
use App\Listeners\EventLogListener;
use App\Listeners\ScopeCompanyModels;
use App\Listeners\GenerateFactorySystems;
use Illuminate\Auth\Events\Failed as LoginFailed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use OwenIt\Auditing\Events\Audited;
use Spatie\Multitenancy\Events\MadeTenantCurrentEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            EventLogListener::class
        ],
        LoginFailed::class => [
            EventLogListener::class
        ],
        Logout::class => [
            EventLogListener::class
        ],
        PasswordUpdated::class => [
            EventLogListener::class
        ],
        PasswordUpdateFailed::class => [
            EventLogListener::class
        ],
        PasswordResetRequested::class => [
            EventLogListener::class
        ],
        PasswordReset::class => [
            EventLogListener::class
        ],
        PasswordResetFailed::class => [
            EventLogListener::class
        ],
        ServiceScheduled::class => [
            EventLogListener::class
        ],
        ServiceScheduleFailed::class => [
            EventLogListener::class
        ],
        Audited::class => [
            AuditedListener::class,
        ],
        MadeTenantCurrentEvent::class => [
            ScopeCompanyModels::class
        ],
        SystemWasCreated::class => [
            GenerateFactorySystems::class
        ],
        IntegrationCreated::class => [
            CreateBIConnectorListener::class,
        ],
        CompanySubscriptionUpdated::class => [
            CreateBIConnectorListener::class,
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
