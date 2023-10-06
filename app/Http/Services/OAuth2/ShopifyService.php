<?php

namespace App\Http\Services\OAuth2;

use App\Http\Abstracts\SystemOAuth2Abstract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Psr\Http\Message\ResponseInterface;

class ShopifyService extends SystemOAuth2Abstract
{
    private const PERMISSION_MESSAGE_FORMAT = 'host=%s&shop=%s&system=%s&timestamp=%s';

    private const AUTHORISED_MESSAGE_FORMAT = 'code=%s&host=%s&shop=%s&timestamp=%s';

    private string $shop;

    /**
     * Redirect the user of the application to the provider's authentication screen.å
     */
    public function redirect(Request $request): Response
    {
        $request->validate([
            'host'      => ['string', 'required'],
            'hmac'      => ['string', 'required'],
            'timestamp' => ['string', 'required'],
            'shop'      => ['string', 'required', 'regex:/^[a-zA-Z0-9][a-zA-Z0-9\-]*.myshopify.com/'],
        ]);

        $valid = $this->verifyRequest($this->getPermissionMessage($request), $request->hmac);
        abort_if(!$valid, 400, 'HMAC verification failed');
        $this->shop = $request->shop;

        return response(['redirect_url' => $this->getProviderRedirectUrl()], 200)->send();
    }

    public function getProviderRedirectUrl(): string
    {
        return sprintf(
            $this->redirectUrl,
            $this->shop,
            $this->clientId,
            Config::get('system-oauth-2.shopify.scopes'),
            urlencode(Config::get('system-oauth-2.shopify.authorised_redirect_url'))
        );
    }

    protected function getPermissionMessage(Request $request): string
    {
        return sprintf(self::PERMISSION_MESSAGE_FORMAT, $request->host, $request->shop, $request->system, $request->timestamp);
    }

    protected function verifyRequest(string $string, string $hmac): bool
    {
        return hash_hmac('sha256', $string, Config::get('system-oauth-2.shopify.client_secret')) === $hmac;
    }

    /**
     * @param Request $request
     *
     * @return ResponseInterface
     */
    public function requestAccessToken(Request $request): ResponseInterface
    {
        $request->validate([
            'code'      => ['string', 'required'],
            'host'      => ['string', 'required'],
            'hmac'      => ['string', 'required'],
            'timestamp' => ['string', 'required'],
            'shop'      => ['string', 'required', 'regex:/^[a-zA-Z0-9][a-zA-Z0-9\-]*.myshopify.com/'],
        ]);

        $valid = $this->verifyRequest($this->getAuthorisedMessage($request), $request->hmac);
        abort_if(!$valid, 400, 'HMAC verification failed');
        $this->shop = $request->shop;

        return $this->client->post(sprintf(Config::get('system-oauth-2.shopify.access_token_url'), $request->shop), [
            'json' => [
                'client_id' => Config::get('system-oauth-2.shopify.client_id'),
                'client_secret' => Config::get('system-oauth-2.shopify.client_secret'),
                'code' => $request->code,
            ]
        ]);
    }

    protected function getAuthorisedMessage(Request $request): string
    {
        return sprintf(self::AUTHORISED_MESSAGE_FORMAT, $request->code, $request->host, $request->shop, $request->timestamp);
    }

    public function validate(ResponseInterface $response): array
    {
        return array_merge(parent::validate($response), ['store' => $this->shop, 'public_app' => true]);
    }
}
