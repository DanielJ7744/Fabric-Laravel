<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class LinnworksService extends SystemAuthAbstract
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
            'token' => [
                'required',
                'string',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'token' => [
                'filled',
                'string',
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $request = $this->client->post(
                sprintf(
                    'https://api.linnworks.net/%s',
                    config('external-app.authentication_endpoints.linnworks')
                ),
                [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    'form_params' => [
                        'ApplicationId' => config('systems.linnworks.applicationid'),
                        'ApplicationSecret' => config('systems.linnworks.applicationsecret'),
                        'Token' => $this->attributes['token']
                    ]
                ]
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
        return isset($authResult['Token']) && !empty($authResult['Token']);
    }

    public static function getTapestryFormat(array $credentials): array
    {
        $requiredCredentials = [
            'ApplicationId' => config('systems.linnworks.applicationid'),
            'ApplicationSecret' => config('systems.linnworks.applicationsecret')
        ];

        return array_merge($credentials, $requiredCredentials);
    }

    public static function getFabricFormat(array $credentials): array
    {
        unset($credentials['ApplicationId'], $credentials['ApplicationSecret']);

        return $credentials;
    }

    public static function getObfuscatedFields(): array
    {
        return ['Token'];
    }
}
