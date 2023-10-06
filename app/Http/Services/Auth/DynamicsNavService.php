<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use App\Http\Helpers\NTLMHelper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class DynamicsNavService extends SystemAuthAbstract
{
    protected Client $client;

    public function __construct(array $attributes, Client $client)
    {
        parent::__construct($attributes);
        $this->client = $client;
    }

    public static function getRules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                'in:basic,ntlm',
            ],
            'server_instance' => [
                'required',
                'string',
            ],
            'username' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
            'company' => [
                'required',
                'string',
            ],
            'url' => [
                'required',
                'string',
            ],
            'domain' => [
                'string',
                'required_if:type,ntlm',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'type' => [
                'filled',
                'string',
                'in:basic,ntlm',
            ],
            'server_instance' => [
                'filled',
                'string',
            ],
            'username' => [
                'filled',
                'string',
            ],
            'password' => [
                'filled',
                'string',
            ],
            'company' => [
                'filled',
                'string',
            ],
            'url' => [
                'filled',
                'string',
            ],
            'domain' => [
                'filled',
                'string',
            ],
        ];
    }

    public function authenticate(): ?array
    {
        $wsdl = self::getWsdlUrl($this->attributes['url'], $this->attributes['server_instance'], $this->attributes['company']);

        return $this->attributes['type'] === 'basic'
            ? $this->basicAuth($wsdl, $this->attributes['username'], $this->attributes['password'])
            : $this->ntlmAuth(
                $this->attributes['url'],
                $wsdl,
                $this->attributes['domain'],
                $this->attributes['username'],
                $this->attributes['password']
            );
    }

    protected function basicAuth(string $wsdl, string $username, string $password): ?array
    {
        try {
            $response = $this->client->post($wsdl,
                ['headers' => [
                    'Authorization' => sprintf('Basic %s', base64_encode($username . ':' . $password))
                ]]
            );

            return (array) simplexml_load_string($response->getBody()->getContents());
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }
    }

    protected function ntlmAuth(string $url, string $wsdl, string $domain, string $username, string $password): ?array
    {
        try {
            $response = NTLMHelper::authenticate($url, $wsdl, $domain, $username, $password);
            return (array) simplexml_load_string($response->getBody()->getContents());
        } catch (Exception|GuzzleException $e) {
            Log::warning($e->getMessage());

            return null;
        }
    }

    public function verify(?array $authResult): bool
    {
        return isset($authResult['contractRef']) && !empty($authResult['contractRef']);
    }

    protected static function getWsdlUrl(string $url, string $serverInstance, string $company): string
    {
        return sprintf(
            '%s/%s/WS/%s/%s',
            $url,
            $serverInstance,
            $company,
            config('external-app.authentication_endpoints.dynamics_nav')
        );
    }

    public static function getTapestryFormat(array $credentials): array
    {
        return [
            'server' => sprintf('%s/%s/WS', $credentials['url'], $credentials['server_instance']),
            'company' => $credentials['company'],
            'auth_basic' => mb_strtolower($credentials['type']) === 'basic',
            'auth_type' => mb_strtolower($credentials['type']) === 'basic' ? 'CURLAUTH_ANY' : 'CURLAUTH_NTLM',
            'ntlm_domain' => $credentials['domain'] ?? null,
            'ntlm_username' => $credentials['username'],
            'ntlm_password' => $credentials['password'],
            'authorisation_type' => $credentials['authorisation_type'],
            'connector_name' => $credentials['connector_name'],
            'timezone' => $credentials['timezone'],
            'date_format' => $credentials['date_format']
        ];
    }

    public static function getFabricFormat(array $credentials): array
    {
        $urlParts = explode('/', $credentials['server']);

        $credentials['type'] = $credentials['auth_basic'] === true ? 'basic' : 'ntlm';
        $credentials['server_instance'] = $urlParts[3] ?? '';
        $credentials['username'] = $credentials['ntlm_username'];
        $credentials['password'] = $credentials['ntlm_password'];
        $credentials['url'] = sprintf('%s//%s', $urlParts[0], $urlParts[2]) ?? $credentials['server'];
        $credentials['domain'] = $credentials['ntlm_domain'] ?? null;
        unset(
            $credentials['server'],
            $credentials['auth_basic'],
            $credentials['auth_type'],
            $credentials['ntlm_domain'],
            $credentials['ntlm_username'],
            $credentials['ntlm_password']
        );

        return $credentials;
    }

    public static function getObfuscatedFields(): array
    {
        return ['password'];
    }
}
