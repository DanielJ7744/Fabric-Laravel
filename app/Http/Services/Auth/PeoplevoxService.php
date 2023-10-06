<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use App\Rules\Https;
use Exception;
use Illuminate\Support\Facades\Log;
use SoapClient;

class PeoplevoxService extends SystemAuthAbstract
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
            'url' => [
                'required',
                'string',
                new Https(),
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
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
            'url' => [
                'filled',
                'string',
                new Https(),
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $authResult = json_decode(json_encode($this->soapClient->Authenticate(
                (object) [
                    'clientId' => $this->attributes['client_id'],
                    'username' => $this->attributes['username'],
                    'password' => base64_encode($this->attributes['password']),
                ]
            )), true);
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function verify(?array $authResult): bool
    {
        return isset($authResult['AuthenticateResult']['ResponseId'])
            && $authResult['AuthenticateResult']['ResponseId'] === 0;
    }

    public static function getTapestryFormat(array $credentials): array
    {
        $credentials['url'] = sprintf('%s/%s', $credentials['url'], $credentials['client_id']);

        return $credentials;
    }

    public static function getFabricFormat(array $credentials, bool $obfuscate = false): array
    {
        $credentials['url'] = str_replace($credentials['client_id'], '', $credentials['url']);

        return $credentials;
    }

    public static function getObfuscatedFields(): array
    {
        return ['password'];
    }
}
