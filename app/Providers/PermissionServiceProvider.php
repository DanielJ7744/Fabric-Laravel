<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Spatie\Permission\Commands\CacheReset;
use Spatie\Permission\Commands\CreatePermission;
use Spatie\Permission\Commands\CreateRole;
use Spatie\Permission\Commands\Show;
use Spatie\Permission\PermissionRegistrar;

class PermissionServiceProvider extends \Spatie\Permission\PermissionServiceProvider
{
    public function boot(PermissionRegistrar $permissionLoader, Filesystem $filesystem)
    {
        if (function_exists('config_path')) { // function not available and 'publish' not relevant in Lumen
            $this->publishes([
                __DIR__.'/../config/permission.php' => config_path('permission.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_permission_tables.php.stub' => $this->getMigrationFileName($filesystem, 'create_permission_tables.php'),
            ], 'migrations');
        }

        $this->registerMacroHelpers();

        $this->commands([
            CacheReset::class,
            CreateRole::class,
            CreatePermission::class,
            Show::class,
        ]);

        $this->registerModelBindings();

        $permissionLoader->clearClassPermissions();

        $this->app->singleton(PermissionRegistrar::class, function ($app) use ($permissionLoader) {
            return $permissionLoader;
        });
    }
}
