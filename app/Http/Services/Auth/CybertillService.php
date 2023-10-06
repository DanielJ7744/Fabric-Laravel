<?php

namespace App\Http\Services\Auth;

use Exception;
use SoapClient;
use Illuminate\Support\Facades\Log;
use App\Http\Abstracts\SystemAuthAbstract;

class CybertillService extends SystemAuthAbstract
{
    public static function getRules(): array
    {
        return [
            'ct_number' => [
                'required',
                'string',
            ],
            'version' => [
                'required',
                'string',
            ],
            'ct_auth_id' => [
                'required',
                'string',
            ],
            'ct_soap_url' => [
                'required',
                'string',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'ct_number' => [
                'filled',
                'string',
            ],
            'version' => [
                'filled',
                'string',
            ],
            'ct_auth_id' => [
                'filled',
                'string',
            ],
            'ct_soap_url' => [
                'filled',
                'string',
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $url = sprintf('https://%s.c-pos.co.uk/current/CybertillApi_v%s.wsdl.php', $this->attributes['ct_number'], str_replace('.', '_', $this->attributes['version']));
            $client = new SoapClient($url,
                [
                    'trace' => 1,
                    'exceptions' => 1,
                    'connection_timeout' => 120,
                    'http_encoding' => 'gzip'
                ]
            );
            $authResult = [
                'session_id' => $client->authenticate_get($this->attributes['ct_soap_url'], $this->attributes['ct_auth_id'])
            ];
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function verify(?array $authResult): bool
    {
        return !is_null($authResult) && is_string($authResult['session_id']) && !empty($authResult['session_id']);
    }

    public static function getObfuscatedFields(): array
    {
        return ['secret'];
    }
}
