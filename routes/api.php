<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
use CloudCreativity\LaravelJsonApi\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('throttle:20,1')->group(function () {
    Route::post('api/v1/login', 'Api\AuthenticationController@login');
    Route::post('api/v2/register', 'Auth\RegisterController@register')->name('api.v2.register');

    Route::post('api/reset-password', 'Api\Auth\PasswordController@reset')->name('api.password.update');
    Route::post('api/forgot-password', 'Api\Auth\PasswordController@email')->name('api.password.email');
});

Route::middleware(['auth:api', 'scope:access-api'])->group(function () {
    Route::post('api/logout', 'Api\AuthenticationController@logout');
});

Route::middleware(['auth:api', 'scope:access-api'])->prefix('api/v2')->name('api.v2.')->group(function () {
    Route::post('alert-mail', 'Api\AlertMailController@store');
    Route::apiResource('system-types', 'Api\SystemTypeController');
    Route::apiResource('roles', 'Api\RoleController')->only(['index', 'show']);
    Route::apiResource('systems', 'Api\SystemController')->only(['index', 'show']);
    Route::apiResource('factories', 'Api\FactoryController')->only(['index', 'show']);
    Route::apiResource('event-types', 'Api\EventTypeController')->only(['index', 'show']);
    Route::apiResource('filter-types', 'Api\FilterTypeController')->only(['index', 'show']);
    Route::apiResource('filter-fields', 'Api\FilterFieldController')->only(['index', 'show']);
    Route::apiResource('subscriptions', 'Api\SubscriptionController')->only(['index', 'show']);
    Route::apiResource('service-options', 'Api\ServiceOptionController')->only(['index', 'show']);
    Route::apiResource('filter-operators', 'Api\FilterOperatorController')->only(['index', 'show']);
    Route::apiResource('filter-templates', 'Api\FilterTemplateController')->only(['index', 'show']);
    Route::apiResource('authorisation-types', 'Api\AuthorisationTypeController')->only(['index', 'show']);
    Route::apiResource('system-authorisation-types', 'Api\SystemAuthorisationTypeController')->only(['index', 'show']);

    /*
    |--------------------------------------------------------------------------
    | My routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('my')->name('my.')->group(function () {
        Route::get('user', 'Api\MyUserController@show')->name('user.show');
        Route::get('company', 'Api\MyCompanyController@show')->name('company.show');
        Route::put('company', 'Api\MyCompanyController@update')->name('company.update');
        Route::put('password', 'Api\MyPasswordController@update')->name('password.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Company tenant routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['company'])->group(function () {
        Route::apiResource('users', 'Api\UserController');
        Route::apiResource('entities', 'Api\EntityController')->only(['index', 'show']);
        Route::apiResource('webhooks', 'Api\WebhookController');
        Route::apiResource('connectors', 'Api\ConnectorController');
        Route::apiResource('alert-groups', 'Api\AlertGroupController');
        Route::apiResource('integrations', 'Api\IntegrationController');
        Route::apiResource('inbound-endpoints', 'Api\InboundEndpointController');
        Route::apiResource('event-logs', 'Api\EventLogController')->only('index');
        Route::apiResource('service-logs', 'Api\ServiceLogController')->only('index');
        Route::apiResource('transactions', 'Api\TransactionController')->only('index');
        Route::apiResource('sync-reports', 'Api\SyncReportsController')->only(['index']);
        Route::apiResource('factory-system-schemas', 'Api\FactorySystemSchemaController');
        Route::apiResource('default-payloads', 'Api\DefaultPayloadController')->only(['index', 'show']);
        Route::apiResource('service-logs', 'Api\ServiceLogController')->only(['index', 'show']);
        Route::apiResource('users.roles', 'Api\UserRoleController')->only(['update', 'destroy']);
        Route::apiResource('company-subscriptions', 'Api\CompanySubscriptionController')->only('index');
        Route::apiResource('services', 'Api\ServiceController')->only(['index', 'show', 'update', 'destroy']);
        Route::apiResource('integrations.users', 'Api\IntegrationUserController')->only(['store', 'destroy']);
        Route::apiResource('integrations.payment-maps', 'Api\IntegrationPaymentMapController')->only(['index', 'store']);
        Route::apiResource('integrations.shipment-maps', 'Api\IntegrationShipmentMapController')->only(['index', 'store']);
        Route::apiResource('factory-systems', 'Api\FactorySystemController')->only(['index', 'show', 'store']);
        Route::apiResource('factory-systems.factory-system-service-options', 'Api\FactorySystemServiceOptionController')->only(['index', 'show']);
        Route::apiResource('integrations.services', 'Api\IntegrationServiceController')->only(['index', 'store']);
        Route::apiResource('service-templates', 'Api\ServiceTemplateController')->only(['index', 'show', 'store']);
        Route::apiResource('service-templates.options', 'Api\ServiceTemplateOptionController')->only(['index', 'show']);
        Route::apiResource('connectors.oauth-clients', 'Api\ConnectorOauthClientController')->only(['index', 'store', 'destroy']);

        Route::post('users/{userId}/restore', 'Api\UserController@restore');

        Route::post('oauth-2', 'Api\Auth\SystemOAuth2Controller@redirectToProvider');
        Route::post('oauth-2/callback', 'Api\Auth\SystemOAuth2Controller@handleProviderCallback');

        Route::apiResource('mappings', 'Api\MappingController')->only(['store', 'show', 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Patchworks user routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('patchworks')->prefix('patchworks')->name('patchworks.')->group(function () {
        Route::apiResource('user.company', 'Api\PatchworksUserCompanyController')->only('update');
        Route::apiResource('companies', 'Api\PatchworksCompanyController')->only(['index', 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | Patchworks admin routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::apiResource('integrations', 'Api\AdminIntegrationController');
        Route::apiResource('users', 'Api\AdminUserController')->except('store');
        Route::apiResource('users.roles', 'Api\AdminUserRoleController')->only(['update', 'destroy']);
        Route::apiResource('entities', 'Api\AdminEntityController');
        Route::apiResource('services', 'Api\AdminServiceController')->only('update');
        Route::apiResource('company.users', 'Api\AdminCompanyUserController')->only('store');
        Route::apiResource('factory-system-schemas', 'Api\AdminFactorySystemSchemaController');
        Route::apiResource('default-payloads', 'Api\AdminDefaultPayloadController')->only(['store', 'update', 'destroy']);
        Route::apiResource('roles', 'Api\AdminRoleController')->only(['store', 'update', 'destroy']);
        Route::apiResource('systems', 'Api\AdminSystemController')->only(['store', 'update', 'destroy']);
        Route::apiResource('companies', 'Api\AdminCompanyController')->only(['store', 'update', 'destroy']);
        Route::apiResource('integrations.users', 'Api\AdminIntegrationUserController')->only(['store', 'destroy']);
        Route::apiResource('filter-fields', 'Api\AdminFilterFieldController')->only(['store', 'update', 'destroy']);
        Route::apiResource('filter-fields.types', 'Api\AdminFilterFieldTypeController')->only(['update', 'destroy']);
        Route::apiResource('service-options', 'Api\AdminServiceOptionController')->only(['store', 'update', 'destroy']);
        Route::apiResource('factory-systems', 'Api\AdminFactorySystemController')->only(['store', 'update', 'destroy']);
        Route::apiResource('factory-systems.factory-system-service-options', 'Api\AdminFactorySystemServiceOptionController')->only(['store', 'update', 'destroy']);
        Route::apiResource('filter-operators', 'Api\AdminFilterOperatorController')->only(['store', 'update', 'destroy']);
        Route::apiResource('filter-templates', 'Api\AdminFilterTemplateController')->only(['store', 'update', 'destroy']);
        Route::apiResource('company.subscription', 'Api\AdminCompanySubscriptionController')->only(['update', 'destroy']);
        Route::apiResource('service-templates', 'Api\AdminServiceTemplateController');
        Route::apiResource('filter-fields.operators', 'Api\AdminFilterFieldOperatorController')->only(['update', 'destroy']);
        Route::apiResource('service-templates.options', 'Api\AdminServiceTemplateOptionController');
        Route::apiResource('system-authorisation-types', 'Api\AdminSystemAuthorisationTypeController')->only(['store', 'update', 'destroy']);
        Route::apiResource('factories', 'Api\AdminFactoryController')->only(['store', 'update', 'destroy']);
        Route::post('users/{userId}/restore', 'Api\AdminUserController@restore');
    });
});

/**
 * V1 routes are now considered deprecated. Please create v2 endpoints going forward and only maintenance for v1.
 * If time allows, please create a v2 version of an endpoint if it has not been moved over, and remove the v1.
 */
JsonApi::register('v1')->middleware('auth:api', 'scope:access-api')->defaultId('[\d]+')->routes(function (RouteRegistrar $api) {
    $api->resource('report-sync-results')->only('index');
    $api->resource('report-sync-filter-options')->only('index');
    $api->resource('alert-recipients', ['has-one' => ['group']]);
    $api->post('delete-alert-service-recipients-by-service-id', 'AlertServiceRecipientsController@deleteAlertServiceRecipientByServiceId');
    $api->resource('alert-service-recipients', ['has-many' => ['recipients']]);
    $api->resource('alert-types')->only('read')->id('[a-zA-Z_]+');
    $api->resource('alert-groups');
    $api->resource('alert-manager')->only('read', 'index', 'update');
    $api->resource('alert-configs');
    $api->resource('alert-scheduler')->only('read', 'create', 'update')->id('[a-zA-Z_]+');

    /**
     * The service-logs endpoints are used externally by customers (!) so we will need to contact them before removing these endpoints.
     */
    $api->resource('service-logs')->only('index', 'create', 'read')->id('[a-zA-Z\d|_]+');
});

/**
 * ================================
 * API / Microservice Gateway calls
 * ================================
 *
 * Add routes here for when Fabric acts as a gateway for the API of another microservice
 * If the 'controller' method is specified, a controller with the appropriate name must be created
 *
 * DYNAMIC FORWARDING ROUTES
 * These use the specified path to forward the request to the proxied API as-is
 * Removing need to create a route here for every path in the proxied API
 * Use / Extend the GatewayController to make use of this functionality
 * All forwarding routes must be part of a named group, or explicitly named, as the name is used to retrieve config values from config/gateway.php
 *
 * If an explicit route / controller method is desired for any particular path, add routes above the forwarding routes to override them
 */
Route::middleware(['auth:api', 'scope:access-api'])->prefix('api/v1/transform-scripts')->name('transform-scripts.')->group(function () {
    Route::middleware('company')->group(function () {
        Route::apiResource('maps', 'ScriptLibrary\MapController');
        Route::apiResource('maps.values', 'ScriptLibrary\MapValueController')->only(['store', 'update', 'destroy']);
        Route::patch('maps/{map}/values', 'ScriptLibrary\MapValueController@bulkUpdate')->name('bulk-update');
    });

    Route::post('batch', 'ScriptLibrary\BatchController@handle')->name('batch.store');
    Route::get('{path}', 'GatewayController@forwardGet')->name('forward.get')->where('path', '[\w\-_\/ ]+');
    Route::post('{path}', 'GatewayController@forwardPost')->name('forward.post')->where('path', '[\w\-_\/ ]+');
});

/*
|--------------------------------------------------------------------------
| Inbound API
|--------------------------------------------------------------------------
*/

Route::domain(config('inbound.url'))->middleware(['client:access-inbound', 'throttle:60,1'])->prefix('v1')->name('api.v1.inbound.')->group(function () {
    Route::post('{integration_slug}/{endpoint_slug}', 'Api\InboundPayloadController@store')->name('payload.store');
});
