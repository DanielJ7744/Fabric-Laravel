<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CommerceToolsService extends SystemAuthAbstract
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
            'project_key' => [
                'required',
                'string',
            ],
            'client_id' => [
                'required',
                'string',
            ],
            'secret' => [
                'required',
                'string',
            ],
            'scope' => [
                'required',
                'string',
            ],
            'api_url' => [
                'required',
                'string',
                'regex:/https:\/\/api\.(us-central1\.gcp|us-east-2\.aws|europe-west1\.gcp|eu-central-1\.aws|australia-southeast1\.gcp)\.commercetools\.com/',
            ],
            'auth_url' => [
                'required',
                'string',
                'regex:/https:\/\/auth\.(us-central1\.gcp|us-east-2\.aws|europe-west1\.gcp|eu-central-1\.aws|australia-southeast1\.gcp)\.commercetools\.com/',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'project_key' => [
                'filled',
                'string',
            ],
            'client_id' => [
                'filled',
                'string',
            ],
            'secret' => [
                'filled',
                'string',
            ],
            'scope' => [
                'filled',
                'string',
            ],
            'api_url' => [
                'filled',
                'string',
                'regex:/https:\/\/api\.(us-central1\.gcp|us-east-2\.aws|europe-west1\.gcp|eu-central-1\.aws|australia-southeast1\.gcp)\.commercetools\.com/',
            ],
            'auth_url' => [
                'filled',
                'string',
                'regex:/https:\/\/auth\.(us-central1\.gcp|us-east-2\.aws|europe-west1\.gcp|eu-central-1\.aws|australia-southeast1\.gcp)\.commercetools\.com/',
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $request = $this->client->post(
                sprintf(
                    '%s/oauth/token',
                    $this->attributes['auth_url']
                ),
                [
                    'auth' => [
                        $this->attributes['client_id'],
                        $this->attributes['secret']
                    ],
                    'form_params' => [
                        'grant_type' => 'client_credentials',
                        'scope' => $this->attributes['scope']
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
        return isset($authResult['access_token'], $authResult['expires_in']);
    }

    public static function getObfuscatedFields(): array
    {
        return ['secret'];
    }
}
