<?php

namespace App\Providers;

use App\Adapters\HasuraAdapter;
use App\Http\Interfaces\ConnectorAuthManagerInterface;
use App\Http\Interfaces\SystemAuthManagerInterface;
use App\Http\Interfaces\SystemOAuth2ManagerInterface;
use App\Http\Interfaces\SystemWebhookManagerInterface;
use App\Http\Managers\ConnectorAuthManager;
use App\Http\Managers\SystemAuthManager;
use App\Http\Managers\SystemOAuth2Manager;
use App\Http\Managers\SystemWebhookManager;
use App\Models\Tapestry\Connector;
use CloudCreativity\LaravelJsonApi\LaravelJsonApi;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        SystemAuthManagerInterface::class => SystemAuthManager::class,
        SystemOAuth2ManagerInterface::class => SystemOAuth2Manager::class,
        SystemWebhookManagerInterface::class => SystemWebhookManager::class,
        ConnectorAuthManagerInterface::class => ConnectorAuthManager::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::model('connector', Connector::class);

        LaravelJsonApi::defaultApi('v1');

        $this->app->bind('hasura', fn () => new HasuraAdapter(
            config('database.hasura.endpoint'),
            config('database.hasura.username'),
            config('database.hasura.password')
        ));
    }
}
