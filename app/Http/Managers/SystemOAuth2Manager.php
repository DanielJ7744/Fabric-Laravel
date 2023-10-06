<?php

namespace App\Http\Managers;

use App\Http\Abstracts\SystemOAuth2Abstract;
use App\Http\Interfaces\SystemOAuth2ManagerInterface;
use App\Http\Services\OAuth2\LightspeedService;
use App\Http\Services\OAuth2\NetSuiteRestService;
use App\Http\Services\OAuth2\ShopifyService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Manager;
use InvalidArgumentException;

class SystemOAuth2Manager extends Manager implements SystemOAuth2ManagerInterface
{
    /**
     * This method is called by the ConnectorAuth facade, each method within ConnectorAuthManager will call getService()
     *
     * @return string
     */
    public static function getLightspeedService(): string
    {
        return LightspeedService::class;
    }

    protected function createLightspeedDriver(): SystemOAuth2Abstract
    {
        return $this->buildProvider(LightspeedService::class, Config::get('system-oauth-2.lightspeed'));
    }

    /**
     * This method is called by the ConnectorAuth facade, each method within ConnectorAuthManager will call getService()
     *
     * @return string
     */
    public static function getShopifyService(): string
    {
        return ShopifyService::class;
    }

    protected function createShopifyDriver(): SystemOAuth2Abstract
    {
        return $this->buildProvider(ShopifyService::class, Config::get('system-oauth-2.shopify'));
    }

    /**
     * This method is called by the ConnectorAuth facade, each method within ConnectorAuthManager will call getService()
     *
     * @return string
     */
    public static function getNetSuiteRestService(): string
    {
        return NetSuiteRestService::class;
    }

    protected function createNetSuiteRestDriver(): SystemOAuth2Abstract
    {
        return $this->buildProvider(NetSuiteRestService::class, Config::get('system-oauth-2.netsuite'));
    }

    /**
     * Build an OAuth 2 provider instance.
     *
     * @param string $provider
     * @param array $config
     *
     * @return SystemOAuth2Abstract
     */
    public function buildProvider(string $provider, array $config): SystemOAuth2Abstract
    {
        return new $provider(
            $config['client_id'],
            $config['client_secret'],
            $config['redirect_url'],
            new Client()
        );
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
