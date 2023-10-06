<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use Illuminate\Support\Facades\Log;
use SoapClient;
use SoapHeader;

class VisualsoftService extends SystemAuthAbstract
{
    protected SoapClient $soapClient;

    public function __construct(array $attributes, SoapClient $soapClient)
    {
        parent::__construct($attributes);
        $this->soapClient = $soapClient;
    }

    public static function getRules(): array
    {
        return [
            'url' => [
                'required',
                'url',
            ],
            'client_id' => [
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
            'version' => [
                'required',
                'in:3,4,5,6,7',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'url' => [
                'filled',
                'url',
            ],
            'client_id' => [
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
            'version' => [
                'filled',
                'in:3,4,5,6,7',
            ]
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $auth = (object)[
                'ClientID' => $this->attributes['client_id'],
                'Username' => $this->attributes['username'],
                'Password' => $this->attributes['password'],
            ];

            $namespace = sprintf(
                '%s/%s/%s',
                $this->attributes['url'],
                config('external-app.authentication_endpoints.visualsoft'),
                $this->attributes['version']
            );

            $header = new SoapHeader(
                $namespace,
                'VSAuth',
                $auth
            );

            $this->soapClient->__setSoapHeaders($header);
            $response = $this->soapClient->__soapCall('HelloWorld', []);
            $authResult = $response === 'hello world' ? ['hello world' => true] : [];
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function verify(?array $authResult): bool
    {
        return !is_null($authResult) && isset($authResult['hello world']);
    }

    public static function getObfuscatedFields(): array
    {
        return ['password'];
    }

    public static function getTapestryFormat(array $credentials): array
    {
        $credentials['visualsoft_soap_url'] = $credentials['url'];
        $credentials['api_version'] = 5;
        unset($credentials['url']);

        return parent::getTapestryFormat($credentials);
    }

    public static function getFabricFormat(array $credentials): array
    {
        $credentials['url'] = $credentials['visualsoft_soap_url'];
        unset($credentials['visualsoft_soap_url'], $credentials['api_version']);

        return parent::getFabricFormat($credentials);
    }
}
