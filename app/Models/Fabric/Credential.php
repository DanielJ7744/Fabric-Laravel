<?php

namespace App\Models\Fabric;

use Illuminate\Contracts\Routing\UrlRoutable;

class Credential implements UrlRoutable
{
    private string $id;
    private string $credentials;
    private System $system;
    private Integration $integration;
    private string $environment;

    /** @var array Fields to hide before sending to the front-end */
    private array $fieldsToObfuscate = ['password', 'OAUTH_TOKEN_ID', 'OAUTH_TOKEN_SECRET', 'api_key', 'shared_secret'];

    public function __construct(string $id, string $credentials, System $system, Integration $integration, string $environment)
    {
        $this->id = $id;
        $this->credentials = $credentials;
        $this->system = $system;
        $this->integration = $integration;
        $this->environment = $environment;
    }

    public function getRouteKey(): string
    {
        return $this->id;
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function resolveRouteBinding($value)
    {
        return null;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCredentials(): string
    {
        return $this->obfuscateCredentials();
    }

    /**
     * Obscure/remove credentials that should not be seen on the front-end
     *
     * @return string
     */
    private function obfuscateCredentials(): string
    {
        $decodedCredentials = json_decode($this->credentials, true);
        foreach ($this->fieldsToObfuscate as $field) {
            if (!isset($decodedCredentials[$field])) {
                continue;
            }
            $decodedCredentials[$field] = str_repeat('*', 5);
        }

        return json_encode($decodedCredentials);
    }

    /**
     * @return System
     */
    public function getSystem(): System
    {
        return $this->system;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @return Integration
     */
    public function getIntegration(): Integration
    {
        return $this->integration;
    }
}
