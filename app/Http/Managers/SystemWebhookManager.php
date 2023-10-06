<?php

namespace App\Http\Managers;

use App\Http\Abstracts\ConnectorAuthAbstract;
use App\Http\Abstracts\SystemWebhookAbstract;
use App\Http\Helpers\SoapHelper;
use App\Http\Interfaces\SystemWebhookManagerInterface;
use App\Http\Services\Webhook\PeoplevoxService;
use App\Http\Services\Webhook\ShopifyService;
use App\Http\Services\Auth\ShopifyService as ShopifyAuthService;
use App\Http\Services\Auth\PeoplevoxService as PeoplevoxAuthService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use InvalidArgumentException;
use SoapClient;
use SoapFault;
use SoapHeader;

class SystemWebhookManager extends Manager implements SystemWebhookManagerInterface
{
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @param  null|ConnectorAuthAbstract  $authoriser
     *
     * @return SystemWebhookAbstract
     *
     * @throws InvalidArgumentException
     */
    public function driver($driver = null, array $attributes = null, ?ConnectorAuthAbstract $authoriser = null): SystemWebhookAbstract
    {
        if (is_null($driver)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to resolve NULL driver for [%s].', static::class
            ));
        }

        if (is_null($authoriser)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to resolve NULL authoriser for [%s].', static::class
            ));
        }

        if (! isset($this->drivers[$driver])) {
            $this->drivers[$driver] = $this->createDriver($driver, $attributes, $authoriser);
        }

        return $this->drivers[$driver];
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    protected function createDriver($driver, array $attributes = null, ConnectorAuthAbstract $authoriser = null)
    {
        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driver);
        } else {
            $method = 'create'.Str::studly($driver).'Driver';

            if (method_exists($this, $method)) {
                return $this->$method($attributes, $authoriser);
            }
        }

        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    public function getRules(string $driver): array
    {
        $method = 'get'.Str::studly($driver).'Service';
        if (!method_exists($this, $method)) {
            throw new InvalidArgumentException("Driver [$driver] not supported.");
        }

        $provider = $this->$method();

        return $provider::getRules();
    }

    protected function getPeoplevoxService(): string
    {
        return PeoplevoxService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $attributes
     * @param PeoplevoxAuthService $authoriser
     *
     * @return SystemWebhookAbstract
     * @throws SoapFault
     */
    protected function createPeoplevoxDriver(array $attributes, PeoplevoxAuthService $authoriser): SystemWebhookAbstract
    {
        $wsdl = sprintf(
            '%s/%s/%s',
            $authoriser->attributes['url'],
            $authoriser->attributes['client_id'],
            config('external-app.authentication_endpoints.peoplevox')
        );

        $authResponse = $authoriser->authenticate();
        abort_if(!$authoriser->verify($authResponse), 422, 'Could not authenticate to target system');
        $sessionId = last(explode(',', Arr::get($authResponse, 'AuthenticateResult.Detail')));

        $authHeader = new SoapHeader(
            'http://www.peoplevox.net/',
            'UserSessionCredentials',
            ['UserId' => 0, 'ClientId' => $authoriser->attributes['client_id'], 'SessionId' => $sessionId]
        );
        $soapClient = new SoapClient($wsdl, SoapHelper::getSoapParameters());
        $soapClient->__setSoapHeaders($authHeader);

        return App::make(PeoplevoxService::class, [
            'attributes' => $attributes,
            'authoriser' => $authoriser,
            'soapClient' => $soapClient
        ]);
    }

    protected function getShopifyService(): string
    {
        return ShopifyService::class;
    }

    /**
     * Create an instance of the specified driver.
     *
     * @param array $attributes
     * @param ShopifyAuthService $authoriser
     *
     * @return SystemWebhookAbstract
     */
    protected function createShopifyDriver(array $attributes, ShopifyAuthService $authoriser): SystemWebhookAbstract
    {
        return App::make(ShopifyService::class, [
            'attributes' => $attributes,
            'authoriser' => $authoriser
        ]);
    }

    /**
     * Get the default driver name.
     *
     * @throws InvalidArgumentException
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No system driver was specified.');
    }
}
