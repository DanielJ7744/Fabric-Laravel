<?php

namespace App\Http\Abstracts;

use App\Exceptions\InvalidAccessTokenResponseException;
use App\Http\Interfaces\SystemOAuth2ProviderInterface;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Throwable;

abstract class SystemOAuth2Abstract extends ConnectorAuthAbstract implements SystemOAuth2ProviderInterface
{
    protected string $clientId;

    protected string $clientSecret;

    protected string $redirectUrl;

    protected Client $client;

    public function __construct(string $clientId, string $clientSecret, string $redirectUrl, Client $client)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUrl = $redirectUrl;
        $this->client = $client;
    }

    /**
     * Redirect the user of the application to the provider's authentication screen.
     */
    public function redirect(Request $request): Response
    {
        return response(['redirect_url' => $this->getProviderRedirectUrl()], 200)->send();
    }

    public function getProviderRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     *
     * @throws InvalidAccessTokenResponseException|Throwable
     */
    public function validate(ResponseInterface $response): array
    {
        $responseArray = json_decode($response->getBody()->getContents(), true);
        throw_if(
            !Arr::has($responseArray, ['access_token', 'refresh_token']),
            InvalidAccessTokenResponseException::class,
            ['message' => 'Response does not contain required access_token and refresh_token']
        );

        return $responseArray;
    }

    public static function getObfuscatedFields(): array
    {
        return ['access_token', 'refresh_token'];
    }
}
