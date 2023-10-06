<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class NetsuiteService extends SystemAuthAbstract
{
    protected Client $client;

    public function __construct(array $attributes, Client $client)
    {
        parent::__construct($attributes);
        $this->client = $client;
    }

    private const OAUTH_VERSION = '1.0';
    private const SIGNATURE_METHOD = 'HMAC-SHA256';
    private const ACTION = 'get_bundle_version';

    public static function getRules(): array
    {
        return [
            'oauth_token_id' => [
                'required',
                'string',
            ],
            'oauth_token_secret' => [
                'required',
                'string',
            ],
            'account_id' => [
                'required',
                'string',
            ],
            'deploy_id' => [
                'string',
                'integer',
            ],
            'script_id' => [
                'required',
                'integer',
            ],
            'consumer_key' => [
                'filled',
                'string',
            ],
            'consumer_secret' => [
                'filled',
                'string',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'oauth_token_id' => [
                'filled',
                'string',
            ],
            'oauth_token_secret' => [
                'filled',
                'string',
            ],
            'account_id' => [
                'filled',
                'string',
            ],
            'deploy_id' => [
                'string',
                'integer',
            ],
            'script_id' => [
                'filled',
                'integer',
            ],
            'consumer_key' => [
                'filled',
                'string',
            ],
            'consumer_secret' => [
                'filled',
                'string',
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $restDomain = $this->getRestDomain($this->attributes['account_id']);
            $restletUrl = $this->getRestletUrl(
                $restDomain,
                (int) $this->attributes['script_id'],
                (int) $this->attributes['deploy_id'] ?? 1
            );
            $request = $this->client->get(
                sprintf('%s&action=%s', $restletUrl, self::ACTION),
                ['headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => sprintf('OAuth %s', $this->generateOauthHeader(
                        'GET',
                        $restDomain,
                        $this->attributes['consumer_key'] ?? config('systems.netsuite.consumer_key'),
                        $this->attributes['consumer_secret'] ?? config('systems.netsuite.consumer_secret'),
                        $this->attributes['oauth_token_secret'],
                        $this->attributes['oauth_token_id'],
                        mb_strtoupper($this->attributes['account_id']),
                        (int) $this->attributes['script_id'],
                        (int) $this->attributes['deploy_id'] ?? 1,
                    )),
                ]]
            );

            $authResult = json_decode($request->getBody()->getContents(), true);
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function verify(?array $authResult): bool
    {
        return isset($authResult['success']) && $authResult['success'] === true;
    }

    private function getRestletUrl(string $restDomain, int $scriptId, int $deployId = 1): string
    {
        return sprintf(
            '%s/%s?script=%s&deploy=%s',
            $restDomain,
            config('external-app.authentication_endpoints.netsuite_restlet'),
            $scriptId,
            $deployId
        );
    }

    private function getRestDomain(string $accountId): string
    {
        try {
            $request = $this->client->get(
                sprintf(
                    '%s?account=%s&c=%s',
                    config('external-app.authentication_endpoints.netsuite_datacenter'),
                    $accountId,
                    $accountId
                ),
                ['headers' => [
                    'accept: application/json',
                    'cache-control: no-cache',
                ]]
            );
            $response = json_decode($request->getBody()->getContents(), true);
            if (!isset($response['restDomain'])) {
                throw new Exception('Failed to get datacenter url');
            }
        } catch (Exception $exception) {
            $error = new Error(null, null, 500, null, $exception->getMessage());
            throw new JsonApiException($error);
        }

        return $response['restDomain'];
    }

    private function generateOauthHeader(
        string $method,
        string $restDomain,
        string $consumerKey,
        string $consumerSecret,
        string $tokenSecret,
        string $tokenId,
        string $accountId,
        int $scriptId,
        int $deployId = 1
    ): string {
        $oauthHeaders = [
            'oauth_version' => self::OAUTH_VERSION,
            'oauth_nonce' => $this->generateNonce(),
            'oauth_signature_method' => self::SIGNATURE_METHOD,
            'oauth_consumer_key' => $consumerKey,
            'oauth_token' => $tokenId,
            'oauth_timestamp' => time(),
        ];

        $oauthSignature = $this->generateOauthSignature(
            $method,
            $restDomain,
            $oauthHeaders,
            $deployId,
            $scriptId,
            $tokenSecret,
            $consumerSecret
        );

        $oauthHeaders = array_merge(['oauth_signature' => $oauthSignature], $oauthHeaders);
        $oauthHeaders['realm'] = $accountId;
        $encodedOauthHeaders = array_map('rawurlencode', $oauthHeaders);

        $headers = [];
        foreach ($encodedOauthHeaders as $header => $value) {
            $headers[] = sprintf('%s="%s"', $header, $value);
        }

        return implode(', ', $headers);
    }

    private function generateOauthSignature(
        string $method,
        string $restDomain,
        array $params,
        string $deployId,
        string $scriptId,
        string $tokenSecret,
        string $consumerSecret
    ): string {
        $params = array_merge($params, [
            'deploy' => $deployId,
            'script' => $scriptId,
            'action' => self::ACTION,
        ]);
        ksort($params);
        $sortedParams = [];
        foreach ($params as $key => $param) {
            if (is_bool($param)) {
                $param = (int)$param;
            }

            $sortedParams[] = sprintf('%s=%s', $key, $param);
        }
        $baseUrl = sprintf('%s/%s', $restDomain, config('external-app.authentication_endpoints.netsuite_restlet'));
        $baseString = sprintf('%s&%s&%s', $method, urlencode($baseUrl), urlencode(implode('&', $sortedParams)));
        $sigString = sprintf('%s&%s', urlencode($consumerSecret), urlencode($tokenSecret));

        return base64_encode(hash_hmac('sha256', $baseString, $sigString, true));
    }

    private function generateNonce(): string
    {
        return md5(base64_encode(openssl_random_pseudo_bytes(64, $var)));
    }

    public static function getTapestryFormat(array $credentials): array
    {
        $credentials = array_change_key_case($credentials, CASE_UPPER);
        $credentials['uses_oauth'] = true;

        return $credentials;
    }

    public static function getFabricFormat(array $credentials): array
    {
        $credentials = array_change_key_case($credentials, CASE_LOWER);
        unset($credentials['uses_oauth']);

        return $credentials;
    }

    public static function getObfuscatedFields(): array
    {
        return ['OAUTH_TOKEN_ID', 'OAUTH_TOKEN_SECRET', 'CONSUMER_KEY', 'CONSUMER_SECRET'];
    }
}
