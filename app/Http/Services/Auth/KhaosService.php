<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use App\Rules\Khaos\Https;
use SoapClient;

class KhaosService extends SystemAuthAbstract
{
    protected ?SoapClient $soapClient;

    public function __construct(array $attributes, ?SoapClient $soapClient)
    {
        parent::__construct($attributes);
        $this->soapClient = $soapClient;
    }

    public static function getRules(): array
    {
        return [
            'url' => [
                'required',
                'string',
                new Https()
            ]
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'url' => [
                'filled',
                'string',
                new Https()
            ]
        ];
    }

    public function authenticate(): ?array
    {
        if (!is_null($this->soapClient)) {
            return ['valid' => true];
        }

        return null;
    }

    public function verify(?array $authResult): bool
    {
        return isset($authResult['valid']);
    }

    public static function getObfuscatedFields(): array
    {
        return []; // the only field is URL which doesn't need to be obfuscated
    }
}
